<?php
/**
 * User entity, store users into database
 */
namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Asserts;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table
 * 
 * @Hateoas\Relation(
 *      name = "self",
 *      href = @Hateoas\Route(
 *          "user_details",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      )
 * )
 * @Hateoas\Relation(
 *      name = "modify",
 *      href = @Hateoas\Route(
 *          "user_patch",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      )
 * )
 * @Hateoas\Relation(
 *      name = "delete",
 *      href = @Hateoas\Route(
 *          "user_delete",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      )
 * )
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * 
     * @Serializer\Groups({"Default"})
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * 
     * @Serializer\Groups({"Default", "ClientView", "AdminView"})
     * 
     * @Asserts\NotBlank
     */
    private $username;

    /**
     * @ORM\Column(type="string")
     * 
     * @Serializer\Groups({"Default", "ClientView", "AdminView"})
     * 
     * @Asserts\NotBlank
     * @Asserts\Email(
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     */
    private $email;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="CASCADE")
     * 
     * @Serializer\Groups({"AdminView"})
     */
    private $client;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="client")
     * 
     * @Serializer\Groups({"AdminView"})
     */
    private $users;

    /**
     * @ORM\Column(type="json")
     * 
     * @Serializer\Groups({"AdminView"})
     */
    private $roles = [];

    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getClient(): User
    {
        return $this->client;
    }

    public function setClient(User $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getUsers(): ?Collection
    {
        return $this->users;
    }

    public function setUser(User $user): self
    {
        if(!$this->users->contains($user)) {

            $this->users[] = $user;

            $user->setClient($this);

        }
        
        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

}
