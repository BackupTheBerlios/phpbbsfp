<?php
//--------------------------------------------------------------------------------------------------
//
// $Id: postgres.php,v 1.1 2004/08/30 21:30:06 dmaj007 Exp $
//
// FILENAME  : postgres.php
// STARTED   : Tue Jan 1, 2004
// COPYRIGHT : © 2003, 2004 Project Minerva Team and © 2001, 2003 The phpBB Group
// WWW       : http://www.project-minerva.org/
// LICENCE   : GPL v2.0 [ see /docs/COPYING ]
//
//--------------------------------------------------------------------------------------------------

if(!defined("SQL_LAYER"))
{

define("SQL_LAYER","postgresql");

class sql_db
{

    var $db_connect_id;
    var $query_result;
    var $in_transaction = 0;
    var $row = array();
    var $rowset = array();
    var $rownum = array();
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

        $this->connect_string = "";

        if( $sqluser )
        {
            $this->connect_string .= "user=$sqluser ";
        }

        if( $sqlpassword )
        {
            $this->connect_string .= "password=$sqlpassword ";
        }

        if( $sqlserver )
        {
            if( ereg(":", $sqlserver) )
            {
                list($sqlserver, $sqlport) = split(":", $sqlserver);
                $this->connect_string .= "host=$sqlserver port=$sqlport ";
            }
            else
            {
                if( $sqlserver != "localhost" )
                {
                    $this->connect_string .= "host=$sqlserver ";
                }
            }
        }

        if( $database )
        {
            $this->dbname = $database;
            $this->connect_string .= "dbname=$database";
        }

        $this->persistency = $persistency;

        $this->db_connect_id = ( $this->persistency ) ? pg_pconnect($this->connect_string) : pg_connect($this->connect_string);

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

        if( $this->db_connect_id )
        {
            //
            // Commit any remaining transactions
            //
            if( $this->in_transaction )
            {
                @pg_exec($this->db_connect_id, "COMMIT");
            }

            if( $this->query_result )
            {
                @pg_freeresult($this->query_result);
            }

            $mtime = microtime();
            $mtime = explode(" ",$mtime);
            $mtime = $mtime[1] + $mtime[0];
            $endtime = $mtime;

            $this->sql_time += $endtime - $starttime;

            return @pg_close($this->db_connect_id);
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
    function sql_query($query = "", $transaction = false)
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;

        //
        // Remove any pre-existing queries
        //
        unset($this->query_result);
        if( $query != "" )
        {
            $this->num_queries++;

            $query = preg_replace("/LIMIT ([0-9]+),([ 0-9]+)/", "LIMIT \\2 OFFSET \\1", $query);

            if( $transaction == BEGIN_TRANSACTION && !$this->in_transaction )
            {
                $this->in_transaction = TRUE;

                if( !@pg_exec($this->db_connect_id, "BEGIN") )
                {
                    $mtime = microtime();
                    $mtime = explode(" ",$mtime);
                    $mtime = $mtime[1] + $mtime[0];
                    $endtime = $mtime;

                    $this->sql_time += $endtime - $starttime;

                    return false;
                }
            }

            $this->query_result = @pg_exec($this->db_connect_id, $query);
            if( $this->query_result )
            {
                if( $transaction == END_TRANSACTION )
                {
                    $this->in_transaction = FALSE;

                    if( !@pg_exec($this->db_connect_id, "COMMIT") )
                    {
                        @pg_exec($this->db_connect_id, "ROLLBACK");

                        $mtime = microtime();
                        $mtime = explode(" ",$mtime);
                        $mtime = $mtime[1] + $mtime[0];
                        $endtime = $mtime;

                        $this->sql_time += $endtime - $starttime;

                        return false;
                    }
                }

                $this->last_query_text[$this->query_result] = $query;
                $this->rownum[$this->query_result] = 0;

                unset($this->row[$this->query_result]);
                unset($this->rowset[$this->query_result]);

                $mtime = microtime();
                $mtime = explode(" ",$mtime);
                $mtime = $mtime[1] + $mtime[0];
                $endtime = $mtime;

                $this->sql_time += $endtime - $starttime;

                return $this->query_result;
            }
            else
            {
                if( $this->in_transaction )
                {
                    @pg_exec($this->db_connect_id, "ROLLBACK");
                }
                $this->in_transaction = FALSE;

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
            if( $transaction == END_TRANSACTION && $this->in_transaction )
            {
                $this->in_transaction = FALSE;

                if( !@pg_exec($this->db_connect_id, "COMMIT") )
                {
                    @pg_exec($this->db_connect_id, "ROLLBACK");

                    $mtime = microtime();
                    $mtime = explode(" ",$mtime);
                    $mtime = $mtime[1] + $mtime[0];
                    $endtime = $mtime;

                    $this->sql_time += $endtime - $starttime;

                    return false;
                }
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
            $query_id = $this->query_result;
        }

        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;

        $this->sql_time += $endtime - $starttime;

        return ( $query_id ) ? @pg_numrows($query_id) : false;
    }

    function sql_numfields($query_id = 0)
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;

        if( !$query_id )
        {
            $query_id = $this->query_result;
        }

        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;

        $this->sql_time += $endtime - $starttime;

        return ( $query_id ) ? @pg_numfields($query_id) : false;
    }

    function sql_fieldname($offset, $query_id = 0)
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;

        if( !$query_id )
        {
            $query_id = $this->query_result;
        }

        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;

        $this->sql_time += $endtime - $starttime;

        return ( $query_id ) ? @pg_fieldname($query_id, $offset) : false;
    }

    function sql_fieldtype($offset, $query_id = 0)
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;

        if( !$query_id )
        {
            $query_id = $this->query_result;
        }

        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;

        $this->sql_time += $endtime - $starttime;

        return ( $query_id ) ? @pg_fieldtype($query_id, $offset) : false;
    }

    function sql_fetchrow($query_id = 0)
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;

        if( !$query_id )
        {
            $query_id = $this->query_result;
        }

        if($query_id)
        {
            $this->row = @pg_fetch_array($query_id, $this->rownum[$query_id]);

            if( $this->row )
            {
                $this->rownum[$query_id]++;

                $mtime = microtime();
                $mtime = explode(" ",$mtime);
                $mtime = $mtime[1] + $mtime[0];
                $endtime = $mtime;

                $this->sql_time += $endtime - $starttime;

                return $this->row;
            }
        }

        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;

        $this->sql_time += $endtime - $starttime;

        return false;
    }

    function sql_fetchrowset($query_id = 0)
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;

        if( !$query_id )
        {
            $query_id = $this->query_result;
        }

        if( $query_id )
        {
            unset($this->rowset[$query_id]);
            unset($this->row[$query_id]);
            $this->rownum[$query_id] = 0;

            while( $this->rowset = @pg_fetch_array($query_id, $this->rownum[$query_id], PGSQL_ASSOC) )
            {
                $result[] = $this->rowset;
                $this->rownum[$query_id]++;
            }

            $mtime = microtime();
            $mtime = explode(" ",$mtime);
            $mtime = $mtime[1] + $mtime[0];
            $endtime = $mtime;

            $this->sql_time += $endtime - $starttime;

            return $result;
        }

        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;

        $this->sql_time += $endtime - $starttime;

        return false;
    }

    function sql_fetchfield($field, $row_offset=-1, $query_id = 0)
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;

        if( !$query_id )
        {
            $query_id = $this->query_result;
        }

        if( $query_id )
        {
            if( $row_offset != -1 )
            {
                $this->row = @pg_fetch_array($query_id, $row_offset, PGSQL_ASSOC);
            }
            else
            {
                if( $this->rownum[$query_id] )
                {
                    $this->row = @pg_fetch_array($query_id, $this->rownum[$query_id]-1, PGSQL_ASSOC);
                }
                else
                {
                    $this->row = @pg_fetch_array($query_id, $this->rownum[$query_id], PGSQL_ASSOC);

                    if( $this->row )
                    {
                        $this->rownum[$query_id]++;
                    }
                }
            }

            $mtime = microtime();
            $mtime = explode(" ",$mtime);
            $mtime = $mtime[1] + $mtime[0];
            $endtime = $mtime;

            $this->sql_time += $endtime - $starttime;

            return $this->row[$field];
        }

        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;

        $this->sql_time += $endtime - $starttime;

        return false;
    }

    function sql_rowseek($offset, $query_id = 0)
    {

        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;

        if(!$query_id)
        {
            $query_id = $this->query_result;
        }

        if( $query_id )
        {
            if( $offset > -1 )
            {
                $this->rownum[$query_id] = $offset;

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

        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;

        $this->sql_time += $endtime - $starttime;

        return false;
    }

    function sql_nextid()
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;

        $query_id = $this->query_result;

        if($query_id && $this->last_query_text[$query_id] != "")
        {
            if( preg_match("/^INSERT[\t\n ]+INTO[\t\n ]+([a-z0-9\_\-]+)/is", $this->last_query_text[$query_id], $tablename) )
            {
                $query = "SELECT currval('" . $tablename[1] . "_id_seq') AS last_value";
                $temp_q_id =  @pg_exec($this->db_connect_id, $query);
                if( !$temp_q_id )
                {
                    $mtime = microtime();
                    $mtime = explode(" ",$mtime);
                    $mtime = $mtime[1] + $mtime[0];
                    $endtime = $mtime;

                    $this->sql_time += $endtime - $starttime;

                    return false;
                }

                $temp_result = @pg_fetch_array($temp_q_id, 0, PGSQL_ASSOC);

                $mtime = microtime();
                $mtime = explode(" ",$mtime);
                $mtime = $mtime[1] + $mtime[0];
                $endtime = $mtime;

                $this->sql_time += $endtime - $starttime;

                return ( $temp_result ) ? $temp_result['last_value'] : false;
            }
        }

        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;

        $this->sql_time += $endtime - $starttime;

        return false;
    }

    function sql_affectedrows($query_id = 0)
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;

        if( !$query_id )
        {
            $query_id = $this->query_result;
        }

        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;

        $this->sql_time += $endtime - $starttime;

        return ( $query_id ) ? @pg_cmdtuples($query_id) : false;
    }

    function sql_freeresult($query_id = 0)
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;

        if( !$query_id )
        {
            $query_id = $this->query_result;
        }

        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;

        $this->sql_time += $endtime - $starttime;

        return ( $query_id ) ? @pg_freeresult($query_id) : false;
    }

    function sql_error($query_id = 0)
    {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $starttime = $mtime;

        if( !$query_id )
        {
            $query_id = $this->query_result;
        }

        $result['message'] = @pg_errormessage($this->db_connect_id);
        $result['code'] = -1;

        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $endtime = $mtime;

        $this->sql_time += $endtime - $starttime;

        return $result;
    }

} // class ... db_sql

} // if ... defined

?>