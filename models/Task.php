<?php
require_once 'AbstractModel.php';
class Task extends AbstractModel
{
    private $table = 'task';

    protected $id;
    protected $headline;
    protected $description;
    protected $dueDate;
    protected $solved = false;
    protected $companyId;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Task
     */
    public function setId($id): Task
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHeadline()
    {
        return $this->headline;
    }

    /**
     * @param mixed $headline
     * @return Task
     */
    public function setHeadline($headline): Task
    {
        $this->headline = $headline;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return Task
     */
    public function setDescription($description): Task
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDueDate()
    {
        return $this->dueDate;
    }

    /**
     * @param mixed $dueDate
     * @return Task
     */
    public function setDueDate($dueDate): Task
    {
        $this->dueDate = $dueDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSolved()
    {
        return $this->solved;
    }

    /**
     * @param mixed $solved
     * @return Task
     */
    public function setSolved($solved): Task
    {
        $this->solved = $solved;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * @param mixed $companyId
     * @return Task
     */
    public function setCompanyId($companyId): Task
    {
        $this->companyId = $companyId;
        return $this;
    }

    protected function prepareAndBindData ($stmt) {
        $this->id && $this->id = htmlspecialchars(strip_tags($this->id));
        $this->headline = htmlspecialchars(strip_tags($this->headline));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->dueDate = htmlspecialchars(strip_tags($this->dueDate));
        $this->solved = htmlspecialchars(strip_tags($this->solved));
        !$this->id &&$this->companyId = htmlspecialchars(strip_tags($this->companyId));

        $this->id && $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':headline', $this->headline);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':due_date', date("Y-m-d H:i:s", strtotime($this->dueDate)));
        $solved = (int)$this->solved;
        $stmt->bindParam(':solved', $solved);
        !$this->id && $stmt->bindParam(':company_id', $this->companyId);
    }


    public function create(): int
    {
        $this->conn->beginTransaction();
        $query = 'INSERT INTO 
        ' . $this->table . '
        SET headline = :headline, description = :description,
            due_date = :due_date, company_id = :company_id,
            solved = :solved';

        $stmt = $this->conn->prepare($query);

        $this->prepareAndBindData($stmt);

        if (!$stmt->execute()) { return false; }

        $this->id = $this->conn->lastInsertId();
        return true;
    }


    public function findByPerson($personId)
    {

        $query = 'SELECT t.id, t.headline, t.description, t.due_date, t.solved, t.company_id,
         (SELECT GROUP_CONCAT(p.name SEPARATOR \',\') FROM collaborators cl
         LEFT JOIN person p on p.id = cl.person_id
         WHERE cl.task_id = t.id
        ) as collaborators
        FROM ' . $this->table . ' t
        LEFT JOIN collaborators c ON c.task_id = t.id
        LEFT JOIN person p ON c.person_id = p.id
        WHERE c.person_id = :person_id
        GROUP BY task_id
        ORDER BY task_id DESC';


        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':person_id', $personId);


        $stmt->execute();
        return $stmt;
    }

    public function delete(): bool
    {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        if (!$stmt->execute()) { return false; }
        return true;
    }

    public function update(): bool
    {
        $query = 'UPDATE 
        ' . $this->table . '
        SET headline = :headline, description = :description,
        due_date = :due_date, solved = :solved WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        $this->prepareAndBindData($stmt);

        if (!$stmt->execute()) { return false; }
        return true;
    }
}
