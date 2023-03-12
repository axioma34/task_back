<?php
require_once 'AbstractModel.php';
class Person extends AbstractModel
{
    private $table = 'person';
    protected $id;
    protected $name;
    protected $gender;
    protected $dateOfBirth;
    protected $companyId;
    protected $mail;
    protected $password;
    protected $position;
    protected $status = true;

    protected $companyName = '';

    /**
     * @return string
     */
    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    /**
     * @param string $companyName
     * @return Person
     */
    public function setCompanyName(string $companyName): Person
    {
        $this->companyName = $companyName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Person
     */
    public function setId($id): Person
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Person
     */
    public function setName($name): Person
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gender
     * @return Person
     */
    public function setGender($gender): Person
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * @param mixed $dateOfBirth
     * @return Person
     */
    public function setDateOfBirth($dateOfBirth): Person
    {
        $this->dateOfBirth = $dateOfBirth;
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
     * @return Person
     */
    public function setCompanyId($companyId): Person
    {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return Person
     */
    public function setPassword($password): Person
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     * @return Person
     */
    public function setPosition($position): Person
    {
        $this->position = $position;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return Person
     */
    public function setStatus($status): Person
    {
        $this->status = $status;
        return $this;
    }

    public function getMail() {
        return $this->mail;
    }

    /**
     * @param mixed $mail
     * @return Person
     */
    public function setMail($mail): Person
    {
        $this->mail = $mail;
        return $this;
    }

    protected function prepareAndBindData ($stmt) {
        $this->id && $this->id = htmlspecialchars(strip_tags($this->id));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->mail = htmlspecialchars(strip_tags($this->mail));
        $this->gender = htmlspecialchars(strip_tags($this->gender));
        $this->dateOfBirth = htmlspecialchars(strip_tags($this->dateOfBirth));
        $this->companyId = htmlspecialchars(strip_tags($this->companyId));
        $this->position = htmlspecialchars(strip_tags($this->position));
        $this->status = htmlspecialchars(strip_tags($this->status));
        $this->password = htmlspecialchars(strip_tags($this->password));


        $this->id && $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':mail', $this->mail);
        $stmt->bindParam(':gender', $this->gender);
        $stmt->bindParam(':date_of_birth', $this->dateOfBirth);
        $stmt->bindParam(':company_id', $this->companyId);
        $stmt->bindParam(':position', $this->position);
        $status = (int)$this->status;
        $stmt->bindParam(':status', $status);
        $this->password && $stmt->bindParam(':password', $this->password);
    }

    public function findOneByEmail()
    {
        $query = 'SELECT
            p.id, p.name, p.gender, p.date_of_birth, p.company_id, p.mail, p.password, p.position, c.name as company_name
        FROM 
            ' . $this->table . ' p
        LEFT JOIN company c ON c.id = p.company_id    
        WHERE p.mail = ? LIMIT 0,1';

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->mail);

        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->id = $row['id'];
        $this->name = $row['name'];
        $this->gender = $row['gender'];
        $this->dateOfBirth = $row['date_of_birth'];
        $this->companyId = $row['company_id'];
        $this->mail = $row['mail'];
        $this->password = $row['password'];
        $this->position = $row['position'];
        $this->status = $row['status'];
        $this->companyName = $row['company_name'];
    }

    public function create(): bool
    {
        $query = 'INSERT INTO 
        ' . $this->table . ' 
        SET
         name = :name,
         mail = :mail,
         gender = :gender,
         date_of_birth = :date_of_birth,
         company_id = :company_id,
         position = :position,
         status = :status,
         password = :password';

        $stmt = $this->conn->prepare($query);

        $this->prepareAndBindData($stmt);

        if ($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function delete(): bool
    {

        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        if (!$stmt->execute()) { return false; }

        return true;
    }

    public function getAll()
    {

        $query = 'SELECT
            p.id, p.name, p.gender, p.date_of_birth, p.company_id, p.mail, p.position, COUNT(c.task_id) as tasks_count,
            p.status, com.name as company_name
        FROM ' . $this->table . ' p
        LEFT JOIN collaborators c ON c.person_id = p.id
        LEFT JOIN company com ON com.id = p.company_id
        GROUP BY company_id, p.id
        ORDER BY company_id DESC';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    public function update(): bool
    {

        $query = 'UPDATE 
        ' . $this->table . '
        SET name = :name, mail = :mail, gender = :gender, date_of_birth = :date_of_birth,
        company_id = :company_id, position = :position, status = :status, password = :password
        WHERE id = :id';

        $stmt = $this->conn->prepare($query);
        $this->prepareAndBindData($stmt);

        if (!$stmt->execute()) { return false; }

        return true;
    }
}
