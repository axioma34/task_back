<?php

require_once 'AbstractModel.php';
class Company extends AbstractModel
{
    private $table = 'company';

    protected $id;
    protected $name;
    protected $mail;
    protected $phone;
    protected $address;
    protected $website;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Company
     */
    public function setId($id): Company
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
     * @return Company
     */
    public function setName($name): Company
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * @param mixed $mail
     * @return Company
     */
    public function setMail($mail): Company
    {
        $this->mail = $mail;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     * @return Company
     */
    public function setPhone($phone): Company
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     * @return Company
     */
    public function setAddress($address): Company
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * @param mixed $website
     * @return Company
     */
    public function setWebsite($website): Company
    {
        $this->website = $website;
        return $this;
    }

    protected function prepareAndBindData($stmt)
    {
        $this->id && $this->id = htmlspecialchars(strip_tags($this->id));
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->mail = htmlspecialchars(strip_tags($this->mail));
        $this->address = htmlspecialchars(strip_tags($this->address));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->website = htmlspecialchars(strip_tags($this->website));

        $this->id && $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':mail', $this->mail);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':website', $this->website);
    }


    public function create(): bool
    {
        $query = 'INSERT INTO 
        ' . $this->table . '
        SET name = :name, mail = :mail, phone = :phone, address = :address, website = :website';

        $stmt = $this->conn->prepare($query);
        $this->prepareAndBindData($stmt);

        if (!$stmt->execute()) { return false; }
        return true;
    }



    public function update(): bool
    {
        $query = 'UPDATE ' . $this->table . ' SET name = :name, mail = :mail,
            phone = :phone, address = :address, website = :website WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $this->prepareAndBindData($stmt);

        if (!$stmt->execute()) { return false; }
        return true;
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
        c.id, c.name, c.mail, c.phone,COUNT(p.id) as users_count, c.address, c.website,
        COUNT(t.id) as tasks_count
        FROM 
            ' . $this->table . ' c
        LEFT JOIN person p ON p.company_id = c.id
        LEFT JOIN task t ON t.company_id = c.id
        GROUP BY id ORDER BY id DESC';

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }
}
