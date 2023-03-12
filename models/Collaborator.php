<?php
require_once 'AbstractModel.php';
class Collaborator extends AbstractModel
{
    private $table = 'collaborators';

    protected $taskId;
    protected $personId;

    /**
     * @return mixed
     */
    public function getTaskId()
    {
        return $this->taskId;
    }

    /**
     * @param mixed $taskId
     * @return Collaborator
     */
    public function setTaskId($taskId): Collaborator
    {
        $this->taskId = $taskId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPersonId()
    {
        return $this->personId;
    }

    /**
     * @param mixed $personId
     * @return Collaborator
     */
    public function setPersonId($personId)
    {
        $this->personId = $personId;
        return $this;
    }



    public function create(): bool
    {

        $query = 'INSERT INTO ' . $this->table . ' SET task_id = :task_id, person_id = :person_id';

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':task_id', $this->taskId);
        $stmt->bindParam(':person_id', $this->personId);


        try {
            $stmt->execute();
            $this->conn->commit();

        } catch(Exception $e) {

            $this->conn->rollback();
            print "Error!: " . $e->getMessage() . "</br>";
            return false;

        }
        return true;
    }
}
