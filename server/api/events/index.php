<?php
include ('../../utils/Rest.php');

Class Events extends Rest
    {
        /*connect to db*/
        private $db = null;
        public function __construct()
        {
            $this->db=DateBase::getInstance();
        }

        public function getEvents()
        {
            $sth = $this->db->query("SELECT e.id, u.login, r.name, description, time_start, time_end, parent, time_create
                FROM booker_events as e
                LEFT JOIN booker_users AS u ON e.user_id = u.id
                LEFT JOIN booker_rooms AS r ON e.room_id = r.id");

            if ($sth)
            {
                $result = $sth->fetchAll(PDO::FETCH_ASSOC);
                $this->createResponse($result, 200);
            }
            else
                $this->createResponse(ERR_999, 404);
        }

        public function postEvents()
        {

            if (Validator::checkParams($this->params, array('id_user', 'id_room', 'description', 'time_start', 'time_end')))
            {
                $id_user = $this->params['id_user'];
                $id_room = $this->params['id_room'];
                $description = $this->params['description'];
                $time_start = Validator::clearData(explode(',', $this->params['time_start']));
                $time_end = Validator::clearData(explode(',', $this->params['time_end']));

                if(count($time_start) == count($time_end))
                {
                    for($i=0; $i<=count($time_start)-1; $i++)
                    {
                        $parent = 0;
                        $time_create = time();

                        $sth = $this->db->query("SELECT id
                            FROM booker_events as e
                            WHERE (e.time_start >= '$time_start[$i]' AND e.time_start <= '$time_end[$i]') 
                            OR (e.time_end >= '$time_start[$i]' AND e.time_end <= '$time_end[$i]')");

                        if ($sth->rowCount() == 0)
                        {
                            $sth = $this->db->prepare("INSERT INTO booker_events 
                                (user_id, room_id, description, time_start, time_end, parent, time_create)
                                VALUES ('$id_user', '$id_room', '$description', '$time_start[$i]', '$time_end[$i]', '$parent', '$time_create')");

                            if (!$sth->execute())
                            {
                                $this->createResponse(var_dump($time_start), 404);
                                exit;
                            }
                        }
                        else
                        {
                            $this->createResponse('Request time:' . $time_start[$i] . ' was rejected, time was reserved before', 404);
                            exit;                       
                        }   
                    }

                    $this->createResponse('Success reserved', 200);

                }
                else
                    $this->createResponse('Incorrect params', 404);
            }
            else
                $this->createResponse('I need params', 404);       
        }

        public function getEventsByParams()
        {
            list ($params['id'], $params['id_user'], $params['time_start'], $params['time_end']) = explode('/', $this->params, 3);
            $params = Validator::clearData($params);

            if ($params['time_start'] && $params['time_end'])
            {
                $sth = $this->db->query("SELECT id
                    FROM booker_events as e
                    WHERE (e.time_start >= '$params[time_start]' AND e.time_start <= '$params[time_end]') 
                    OR (e.time_end >= '$params[time_start]' AND e.time_end <= '$params[time_end]')");

                if ($sth->rowCount()>0)
                {
                    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
                    $this->createResponse($result, 200);
                }
                else
                    $this->createResponse('Empty', 404);

            }
            elseif ($params['id'] || $params['id_user'])
            {
                if($params['id_user'])
                    $where = "WHERE e.user_id = '" . $params['id_user'] . "'";
                else
                    $where = "WHERE e.id = '" . $params['id'] . "'";

                $sth = $this->db->query("SELECT e.id, u.login, r.name, description, time_start, time_end, parent, time_create
                    FROM booker_events as e
                    LEFT JOIN booker_users AS u ON e.user_id = u.id
                    LEFT JOIN booker_rooms AS r ON e.room_id = r.id
                    $where");

                if ($sth->rowCount()>0)
                {
                    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
                    $this->createResponse($result, 200);
                }
                else
                    $this->createResponse('Empty', 404);

            }
            else
                $this->createResponse('I need params', 404);

        } 
    }

$obj = new Events();
$obj->start();
