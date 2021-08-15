<?php

namespace App\Entity;

use App\Entity\Traits\MediaTrait;
use App\Entity\Traits\UserTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Repository\UserRepository;

/**
 * Class User
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass=UserRepository::class)
 *
 * @ORM\AttributeOverrides({
 *     @ORM\AttributeOverride(name="username",
 *          column=@ORM\Column(
 *              name="username",
 *              type="string",
 *              length=255,
 *              nullable=true
 *          )
 *      ),
 *      @ORM\AttributeOverride(name="usernameCanonical",
 *          column=@ORM\Column(
 *             name="usernameCanonical",
 *             type="string",
 *             length=255,
 *             unique=false,
 *             nullable=true
 *          )
 *      )
 * })
 *
 * @ORM\Table(name="v_user")
 * @ORM\MappedSuperclass
 *
 * @UniqueEntity("emailCanonical", errorPath="email", message="fos_user.email.already_used", groups={"AppRegistration", "Profile"})
 * @Vich\Uploadable
 */
class User extends BaseUser
{
    use MediaTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     message="fos_user.email.blank",
     *     groups={"AppRegistration", "Profile"}
     * )
     *
     * @Assert\Length(
     *     min="2",
     *     max="180",
     *     minMessage="fos_user.email.short",
     *     maxMessage="fos_user.email.long",
     *     groups={"AppRegistration", "Profile"}
     * )
     *
     * @Assert\Email(
     *     message="fos_user.email.invalid",
     *     groups={"AppRegistration", "Profile"}
     * )
     */
    protected $email;

    /**
     * @var string
     *
     * @Assert\NotBlank(
     *     message="fos_user.password.blank",
     *     groups={"AppRegistration", "ResetPassword", "ChangePassword"}
     * )
     *
     * @Assert\Length(
     *     min="8",
     *     max="4096",
     *     minMessage="fos_user.password.short",
     *     groups={"AppRegistration", "Profile", "ResetPassword", "ChangePassword"}
     * )
     */
    protected $plainPassword;

    /**
     * @var Commande
     *
     * @ORM\OneToMany(targetEntity=Commande::class, mappedBy="user", cascade={"remove"})
     */
    protected $orders;

    /**
     * @var Post
     *
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="user", cascade={"remove"})
     */
    protected $posts;

    use UserTrait;

    public function __construct()
    {
        parent::__construct();
        $this->__constructUser();

        $this->orders = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }

    /**
     * @return Collection|Commande[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Commande $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
        }

        return $this;
    }

    public function removeOrder(Commande $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
        }

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPosts(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
        }

        return $this;
    }

    public function removePosts(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
        }

        return $this;
    }
}

