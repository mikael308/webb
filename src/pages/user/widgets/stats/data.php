<?php
namespace Web\Pages\User\Widgets\Stats;

use \Web\Session\Security\Authorizer as Authorizer;

class data extends \Web\Framework\Data\Widget
{
    protected $view_user;

    public function __construct()
    {
        $this->view_user = \Web\Database\Read::forumUser(
            getReqUser()
        );
    }

    /**
     * gets information stats of user
     * @return array key: label, value: info-value
     */
    public function getStats()
    {
        $stats = [
            'name' => $this->view_user->getName()
        ];

        $authUser = Authorizer::getAuthorizedUser();
        if ($this->view_user === $authUser || $authUser->isAdmin()) {
            $displayStats["email"] = $this->view_user->getEmail();
        }

        $stats['role'] = $this->view_user->getRole();
        $stats['registered'] = $this->view_user->getRegistered();
        $stats['created threads'] = \Web\Database\Count::forumThreads($this->view_user);
        $stats['posts'] = \Web\Database\Count::forumPosts($this->view_user);
        return $stats;
    }
    
}
