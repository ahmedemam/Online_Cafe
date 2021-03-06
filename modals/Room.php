<?php

namespace App;

use \MongoDB\BSON\ObjectId as ObjectID;
use function \MongoDB\BSON\toPHP as toPHP;
use \MongoDB\Driver\Manager as MongoManager;
use \MongoDB\Driver\BulkWrite as MongoBulkWrite;
use \MongoDB\Driver\Query as MongoQuery;
use \MongoDB\Driver\Exception;
use MongoException;

class Room
{
    private $DATABASE_PATH = '';
    private $DATABASE_NAME = '';
    private $COLLECTION_NAME = '';
    private $connectionManager = '';

    public function __construct()
    {
        $this->DATABASE_PATH = 'mongodb://localhost:27017';
        $this->DATABASE_NAME = 'OnlineCafeDatabase';
        $this->COLLECTION_NAME = 'Room';
        $this->connectionManager = new MongoManager('mongodb://localhost:27017');
    }

    // insert Room document
    public function insertOneRoom($RoomName)
    {
        try {
            if (isset($RoomName) && !empty($RoomName)) {
                $bulkWriteInsert = new MongoBulkWrite;
                $inserted_id = $bulkWriteInsert->insert(["RoomName" => $RoomName]);
                $response = $this->connectionManager->executeBulkWrite($this->DATABASE_NAME . '.' . $this->COLLECTION_NAME, $bulkWriteInsert);
                return var_dump($inserted_id);
            } else {
                return false;
            }
        } catch (MongoException $exception) {
            return $exception->getMessage();
        }
    }

    // delete Room document
    public function deleteOneRoom($RoomId)
    {
        try {
            if (isset($RoomId) && !empty($RoomId)) {
                $filter = ['_id' => new ObjectID($RoomId)];
                $bulkWriteDeleted = new MongoBulkWrite;
                $options = ['limit' => 1];
                $bulkWriteDeleted->delete($filter, $options);
                $response = $this->connectionManager->executeBulkWrite($this->DATABASE_NAME . '.' . $this->COLLECTION_NAME, $bulkWriteDeleted);
                return $response->isAcknowledged();
            } else {
                return false;
            }
        } catch (MongoException $exception) {
            return $exception->getMessage();
        }
    }

    // delete multiple by Room Name
    public function deleteAllRoom($RoomName, $limit)
    {

        try {
            if (isset($RoomName) && !empty($RoomName) && isset($limit) && !empty($limit)) {
                $filter = ['RoomName' => $RoomName];
                $bulkWriteDeleted = new MongoBulkWrite;
                $options = ['limit' => $limit];
                $bulkWriteDeleted->delete($filter, $options);
                $response = $this->connectionManager->executeBulkWrite($this->DATABASE_NAME . '.' . $this->COLLECTION_NAME, $bulkWriteDeleted);
                return $response->isAcknowledged();
            } else {
                return false;
            }
        } catch (MongoException $exception) {
            return $exception->getMessage();
        }
    }

    // update Room document or
    public function updateOneRoom($oldRoomName, $newRoomName, $multi)
    {
        try {
            if (isset($oldRoomName) && !empty($oldRoomName) && isset($newRoomName) && !empty($newRoomName)) {
                $filter = ['RoomName' => $oldRoomName];
                $documentUpdated = ['$set' => ['RoomName' => $newRoomName]];
                $options = ['multi' => $multi, 'upsert' => $multi];
                $bulkWriteUpdated = new MongoBulkWrite;
                $bulkWriteUpdated->update($filter, $documentUpdated, $options);
                $response = $this->connectionManager->executeBulkWrite($this->DATABASE_NAME . '.' . $this->COLLECTION_NAME, $bulkWriteUpdated);
                return $response->isAcknowledged();
            } else {
                return false;
            }
        } catch (MongoException $exception) {
            return $exception->getMessage();
        }
    }

    // getOne Room by RoomID
    public function getOneRoom($RoomId, $limit)
    {
        try {
            if (isset($RoomId) && !empty($RoomId) && isset($limit) && !empty($limit)) {
                $filter = ['_id' => new ObjectID($RoomId)];
                $options = ['limit' => $limit];
                $QueryManager = new MongoQuery($filter, $options);
                $responseCursor = $this->connectionManager->executeQuery($this->DATABASE_NAME . '.' . $this->COLLECTION_NAME, $QueryManager);
                return json_encode($responseCursor->toArray());
            } else {
                return false;
            }
        } catch (MongoException $exception) {
            return $exception->getMessage();
        } catch (Exception\Exception $e) {
        }
    }

    // getMulti Room by Name
    public function getAllRoom()
    {
        try {
            $QueryManager = new MongoQuery([]);
            $responseCursor = $this->connectionManager->executeQuery($this->DATABASE_NAME . '.' . $this->COLLECTION_NAME, $QueryManager);
            return json_encode($responseCursor->toArray());
        } catch (MongoException $exception) {
            return $exception->getMessage();
        } catch (Exception\Exception $e) {
        }

    }
}