<?php

namespace Truelab\KottiFrontendBundle\Security\Authorization\Voter;
use Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class NodeVoter
 * @package Truelab\KottiFrontendBundle\Security\Authorization\Voter
 */
class NodeVoter implements VoterInterface
{

    const VIEW = 'VIEW';

    /**
     * @var RoleHierarchyVoter $roleHierarchyVoter
     */
    private $roleHierarchyVoter;

    /**
     * Checks if the voter supports the given attribute.
     *
     * @param string $attribute An attribute
     *
     * @return bool true if this Voter supports the attribute, false otherwise
     */
    public function supportsAttribute($attribute)
    {
        return in_array($attribute, array(
            self::VIEW
        ));
    }

    /**
     * Checks if the voter supports the given class.
     *
     * @param string $class A class name
     *
     * @return bool true if this Voter can process the class
     */
    public function supportsClass($class)
    {
       return in_array('Truelab\KottiORMBundle\Model\NodeInterface', class_implements($class));
    }

    /**
     * Returns the vote for the given parameters.
     *
     * This method must return one of the following constants:
     * ACCESS_GRANTED, ACCESS_DENIED, or ACCESS_ABSTAIN.
     *
     * @param TokenInterface $token A TokenInterface instance
     * @param object|null $object The object to secure
     * @param array $attributes An array of attributes associated with the method being invoked
     *
     * @return int either ACCESS_GRANTED, ACCESS_ABSTAIN, or ACCESS_DENIED
     */
    public function vote(TokenInterface $token, $object, array $attributes)
    {

        // check if class of this object is supported by this voter
        if (!$this->supportsClass(get_class($object))) {
            return VoterInterface::ACCESS_ABSTAIN;
        }


        // check if the voter is used correct, only allow one attribute
        // this isn't a requirement, it's just one easy way for you to
        // design your voter
        if (1 !== count($attributes)) {
            throw new \InvalidArgumentException(
                'Only one attribute is allowed'
            );
        }

        $action = $attributes[0];

        if($action === self::VIEW) {

            if($object->isPublic()) {
                return VoterInterface::ACCESS_GRANTED;
            }

            if($object->isPrivate()) {
                return $this->roleHierarchyVoter->vote($token, null, array('ROLE_ADMIN'));
            }
        }

        return VoterInterface::ACCESS_ABSTAIN;
    }

    public function setRoleHierarchyVoter(RoleHierarchyVoter $roleHierarchyVoter)
    {
        $this->roleHierarchyVoter = $roleHierarchyVoter;
    }
}
