<?php

/*
 * The MIT License
 *
 * Copyright 2016 Alberto.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Wireframe\Test\Users;

use Doctrine\ORM\Mapping\Table;
use RuntimeException;
use Wireframe\Entity;

/**
 * Description of User
 *
 * @author Alberto Avon <alberto.avon@gmail.com>
 * @Entity
 * @Table(name="users")
 */
class User extends Entity
{

    /**
     * @var string
     * @Column(type="string", length=255, unique=false, nullable=false)
     */
    protected $name;

    /**
     * @var string
     * @Column(type="string", length=255, unique=false, nullable=false)
     */
    protected $surname;

    /**
     * @var string
     * @Column(type="string", length=150, unique=true, nullable=false)
     */
    protected $email;

    /**
     * @var string
     * @Column(type="text", nullable=true)
     */
    protected $notes;

    /**
     * Get name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get surname
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Get email
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set name
     * @param $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Set surname
     * @param $surname
     * @return User
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
        return $this;
    }

    /**
     * Set email
     * @param $email
     * @return User
     */
    public function setEmail($email)
    {
        // Validated e-mail address
        $_email = filter_var($email, FILTER_VALIDATE_EMAIL);

        if (!$_email) {
            throw new RuntimeException('Invalid or empty e-mail address provided');
        }

        $this->email = trim(strtolower($_email));
        return $this;
    }

    /**
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param string $notes
     * @return User
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
        return $this;
    }
    
}
