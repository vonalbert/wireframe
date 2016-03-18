<?php

/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
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
