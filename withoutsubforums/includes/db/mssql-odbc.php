<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: mssql-odbc.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : mssql-odbc.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team and © 2001, 2003 The phpBB Group
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

if(!defined("SQL_LAYER"))
{

define("SQL_LAYER","mssql-odbc");

class sql_db
{

    var $db_connect_id;
    var $result;

    var $next_id;

    var $num_rows = array();
    var $current_row = array();
    var $field_names = array();
    var $field_types = array();
    var $result_rowset = array();

    var $num_queries = 0;
    var $sql_time = 0; // SQL excution time

    //
    // Constructor
    //
    function sql_db($sqlserver, $sqluser, $sqlpassword, $database, $persistency = true)
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;

        $this->persistency = $persistency;
        $this->server = $sqlserver;
        $this->user = $sqluser;
        $this->password = $sqlpassword;
        $this->dbname = $database;

        $this->db_connect_id = ($this->persistency) ? odbc_pconnect($this->server, $this->user, $this->password) : odbc_connect($this->server, $this->user, $this->password);

        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;

        $this->sql_time += $endtime - $starttime;

        return ( $this->db_connect_id ) ? $this->db_connect_id : false;
    }
    //
    // Other base methods
    //
    function sql_close()
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;

        if($this->db_connect_id)
        {
            if( $this->in_transaction )
            {
                @odbc_commit($this->db_connect_id);
            }

            if( count($this->result_rowset) )
            {
                unset($this->result_rowset);
                unset($this->field_names);
                unset($this->field_types);
                unset($this->num_rows);
                unset($this->current_row);
            }

            $mtime = microtime();
            $mtime = explode(" ",$mtime);
            $mtime = $mtime[1] + $mtime[0];
            $endtime = $mtime;

            $this->sql_time += $endtime - $starttime;

            return @odbc_close($this->db_connect_id);
        }
        else
        {
            $mtime = microtime();
            $mtime = explode(" ",$mtime);
            $mtime = $mtime[1] + $mtime[0];
            $endtime = $mtime;

            $this->sql_time += $endtime - $starttime;

            return false;
        }
    }

    //
    // Query method
    //
    function sql_query($query = "", $transaction = FALSE)
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;

        if( $query != "" )
        {
            $this->num_queries++;

            if( $transaction == BEGIN_TRANSACTION && !$this->in_transaction )
            {
                if( !odbc_autocommit($this->db_connect_id, false) )
                {
                    $mtime = microtime();
                    $mtime = explode(" ",$mtime);
                    $mtime = $mtime[1] + $mtime[0];
                    $endtime = $mtime;

                    $this->sql_time += $endtime - $starttime;

                    return false;
                }
                $this->in_transaction = TRUE;
            }

            if( preg_match("/^SELECT(.*?)(LIMIT ([0-9]+)[, ]*([0-9]+)*)?$/s", $query, $limits) )
            {
                $query = $limits[1];

                if( !empty($limits[2]) )
                {
                    $row_offset = ( $limits[4] ) ? $limits[3] : "";
                    $num_rows = ( $limits[4] ) ? $limits[4] : $limits[3];

                    $query = "TOP " . ( $row_offset + $num_rows ) . $query;
                }

                $this->result = odbc_exec($this->db_connect_id, "SELECT $query");

                if( $this->result )
                {
                    if( empty($this->field_names[$this->result]) )
                    {
                        for($i = 1; $i < odbc_num_fields($this->result) + 1; $i++)
                        {
                            $this->field_names[$this->result][] = odbc_field_name($this->result, $i);
                            $this->field_types[$this->result][] = odbc_field_type($this->result, $i);
                        }
                    }

                    $this->current_row[$this->result] = 0;
                    $this->result_rowset[$this->result] = array();

                    $row_outer = ( isset($row_offset) ) ? $row_offset + 1 : 1;
                    $row_outer_max = ( isset($num_rows) ) ? $row_offset + $num_rows + 1 : 1E9;
                    $row_inner = 0;

                    while( odbc_fetch_row($this->result, $row_outer) && $row_outer < $row_outer_max )
                    {
                        for($j = 0; $j < count($this->field_names[$this->result]); $j++)
                        {
                            $this->result_rowset[$this->result][$row_inner][$this->field_names[$this->result][$j]] = stripslashes(odbc_result($this->result, $j + 1));
                        }

                        $row_outer++;
                        $row_inner++;
                    }

                    $this->num_rows[$this->result] = count($this->result_rowset[$this->result]);
                }

            }
            else if( eregi("^INSERT ", $query) )
            {
                $this->result = odbc_exec($this->db_connect_id, $query);

                if( $this->result )
                {
                    $result_id = odbc_exec($this->db_connect_id, "SELECT @@IDENTITY");
                    if( $result_id )
                    {
                        if( odbc_fetch_row($result_id) )
                        {
                            $this->next_id[$this->db_connect_id] = odbc_result($result_id, 1);
                            $this->affected_rows[$this->db_connect_id] = odbc_num_rows($this->result);
                        }
                    }
                }
            }
            else
            {
                $this->result = odbc_exec($this->db_connect_id, $query);

                if( $this->result )
                {
                    $this->affected_rows[$this->db_connect_id] = odbc_num_rows($this->result);
                }
            }

            if( !$this->result )
            {
                if( $this->in_transaction )
                {
                    odbc_rollback($this->db_connect_id);
                    odbc_autocommit($this->db_connect_id, true);
                    $this->in_transaction = FALSE;
                }

                $mtime = microtime();
                $mtime = explode(" ",$mtime);
                $mtime = $mtime[1] + $mtime[0];
                $endtime = $mtime;

                $this->sql_time += $endtime - $starttime;

                return false;
            }

            if( $transaction == END_TRANSACTION && $this->in_transaction )
            {
                $this->in_transaction = FALSE;

                if ( !odbc_commit($this->db_connect_id) )
                {
                    odbc_rollback($this->db_connect_id);
                    odbc_autocommit($this->db_connect_id, true);

                    $mtime = microtime();
                    $mtime = explode(" ",$mtime);
                    $mtime = $mtime[1] + $mtime[0];
                    $endtime = $mtime;

                    $this->sql_time += $endtime - $starttime;

                    return false;
                }
                odbc_autocommit($this->db_connect_id, true);
            }

            odbc_free_result($this->result);

            $mtime = microtime();
            $mtime = explode(" ",$mtime);
            $mtime = $mtime[1] + $mtime[0];
            $endtime = $mtime;

            $this->sql_time += $endtime - $starttime;

            return $this->result;
        }
        else
        {
            if( $transaction == END_TRANSACTION && $this->in_transaction )
            {
                $this->in_transaction = FALSE;

                if ( !@odbc_commit($this->db_connect_id) )
                {
                    odbc_rollback($this->db_connect_id);
                    odbc_autocommit($this->db_connect_id, true);

                    $mtime = microtime();
                    $mtime = explode(" ",$mtime);
                    $mtime = $mtime[1] + $mtime[0];
                    $endtime = $mtime;

                    $this->sql_time += $endtime - $starttime;

                    return false;
                }
                odbc_autocommit($this->db_connect_id, true);
            }

            $mtime = microtime();
            $mtime = explode(" ",$mtime);
            $mtime = $mtime[1] + $mtime[0];
            $endtime = $mtime;

            $this->sql_time += $endtime - $starttime;

            return true;
        }
    }

    //
    // Other query methods
    //
    function sql_numrows($query_id = 0)
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;

        if( !$query_id )
        {
            $query_id = $this->result;
        }

        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;

        $this->sql_time += $endtime - $starttime;

        return ( $query_id ) ? $this->num_rows[$query_id] : false;
    }

    function sql_numfields($query_id = 0)
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;

        if( !$query_id )
        {
            $query_id = $this->result;
        }

        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;

        $this->sql_time += $endtime - $starttime;

        return ( $query_id ) ? count($this->field_names[$query_id]) : false;
    }

    function sql_fieldname($offset, $query_id = 0)
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;

        if( !$query_id )
        {
            $query_id = $this->result;
        }

        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;

        $this->sql_time += $endtime - $starttime;

        return ( $query_id ) ? $this->field_names[$query_id][$offset] : false;
    }

    function sql_fieldtype($offset, $query_id = 0)
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;

        if( !$query_id )
        {
            $query_id = $this->result;
        }

        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;

        $this->sql_time += $endtime - $starttime;

        return ( $query_id ) ? $this->field_types[$query_id][$offset] : false;
    }

    function sql_fetchrow($query_id = 0)
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;

        if( !$query_id )
        {
            $query_id = $this->result;
        }

        if( $query_id )
        {
            $mtime = microtime();
            $mtime = explode(" ",$mtime);
            $mtime = $mtime[1] + $mtime[0];
            $endtime = $mtime;

            $this->sql_time += $endtime - $starttime;

            return ( $this->num_rows[$query_id] && $this->current_row[$query_id] < $this->num_rows[$query_id] ) ? $this->result_rowset[$query_id][$this->current_row[$query_id]++] : false;
        }
        else
        {
            $mtime = microtime();
            $mtime = explode(" ",$mtime);
            $mtime = $mtime[1] + $mtime[0];
            $endtime = $mtime;

            $this->sql_time += $endtime - $starttime;

            return false;
        }
    }

    function sql_fetchrowset($query_id = 0)
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;

        if( !$query_id )
        {
            $query_id = $this->result;
        }

        if( $query_id )
        {
            $mtime = microtime();
            $mtime = explode(" ",$mtime);
            $mtime = $mtime[1] + $mtime[0];
            $endtime = $mtime;

            $this->sql_time += $endtime - $starttime;

            return ( $this->num_rows[$query_id] ) ? $this->result_rowset[$query_id] : false;
        }
        else
        {
            $mtime = microtime();
            $mtime = explode(" ",$mtime);
            $mtime = $mtime[1] + $mtime[0];
            $endtime = $mtime;

            $this->sql_time += $endtime - $starttime;

            return false;
        }
    }

    function sql_fetchfield($field, $row = -1, $query_id = 0)
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;

        if( !$query_id )
        {
            $query_id = $this->result;
        }

        if( $query_id )
        {
            if( $row < $this->num_rows[$query_id] )
            {
                $getrow = ( $row == -1 ) ? $this->current_row[$query_id] - 1 : $row;

                $mtime = microtime();
                $mtime = explode(" ",$mtime);
                $mtime = $mtime[1] + $mtime[0];
                $endtime = $mtime;

                $this->sql_time += $endtime - $starttime;

                return $this->result_rowset[$query_id][$getrow][$this->field_names[$query_id][$field]];

            }
            else
            {
                $mtime = microtime();
                $mtime = explode(" ",$mtime);
                $mtime = $mtime[1] + $mtime[0];
                $endtime = $mtime;

                $this->sql_time += $endtime - $starttime;

                return false;
            }
        }
        else
        {
            $mtime = microtime();
            $mtime = explode(" ",$mtime);
            $mtime = $mtime[1] + $mtime[0];
            $endtime = $mtime;

            $this->sql_time += $endtime - $starttime;

            return false;
        }
    }

    function sql_rowseek($offset, $query_id = 0)
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;

        if( !$query_id )
        {
            $query_id = $this->result;
        }

        if( $query_id )
        {
            $this->current_row[$query_id] = $offset - 1;

            $mtime = microtime();
            $mtime = explode(" ",$mtime);
            $mtime = $mtime[1] + $mtime[0];
            $endtime = $mtime;

            $this->sql_time += $endtime - $starttime;

            return true;
        }
        else
        {
            $mtime = microtime();
            $mtime = explode(" ",$mtime);
            $mtime = $mtime[1] + $mtime[0];
            $endtime = $mtime;

            $this->sql_time += $endtime - $starttime;

            return false;
        }
    }

    function sql_nextid()
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;

        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;

        $this->sql_time += $endtime - $starttime;

        return ( $this->next_id[$this->db_connect_id] ) ? $this->next_id[$this->db_connect_id] : false;
    }

    function sql_affectedrows()
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;

        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;

        $this->sql_time += $endtime - $starttime;

        return ( $this->affected_rows[$this->db_connect_id] ) ? $this->affected_rows[$this->db_connect_id] : false;
    }

    function sql_freeresult($query_id = 0)
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;

        if( !$query_id )
        {
            $query_id = $this->result;
        }

        unset($this->num_rows[$query_id]);
        unset($this->current_row[$query_id]);
        unset($this->result_rowset[$query_id]);
        unset($this->field_names[$query_id]);
        unset($this->field_types[$query_id]);

        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;

        $this->sql_time += $endtime - $starttime;

        return true;
    }

    function sql_error()
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;

        $error['code'] = odbc_error($this->db_connect_id);
        $error['message'] = odbc_errormsg($this->db_connect_id);

        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;

        $this->sql_time += $endtime - $starttime;

        return $error;
    }

} // class sql_db

} // if ... define

?>