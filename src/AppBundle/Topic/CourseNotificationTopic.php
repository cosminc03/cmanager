<?php

namespace AppBundle\Topic;

use Gos\Bundle\WebSocketBundle\Client\ClientManipulatorInterface;
use Gos\Bundle\WebSocketBundle\Topic\PushableTopicInterface;
use Gos\Bundle\WebSocketBundle\Topic\TopicInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
use Gos\Bundle\WebSocketBundle\Router\WampRequest;

class CourseNotificationTopic implements TopicInterface, PushableTopicInterface
{
    const CREATE_COURSE_MODULE = 'create_course_module';
    const NEW_COURSE_ANNOUNCEMENT = 'new_course_announcement';

    protected $clientManipulator;

    /**
     * UserNotificationTopic constructor.
     *
     * @param ClientManipulatorInterface $clientManipulator
     */
    public function __construct(ClientManipulatorInterface $clientManipulator)
    {
        $this->clientManipulator = $clientManipulator;
    }

    /**
     * This will receive any Subscription requests for this topic.
     *
     * @param ConnectionInterface $connection
     * @param Topic               $topic
     * @param WampRequest         $request
     */
    public function onSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
    {
        $user = $this->clientManipulator->getClient($connection);
        dump($user);
        $data = explode('/', $topic->getId());
        $urlId = $data[4];
        dump($urlId);

        /*if (intval($urlId) !== $user->getId()) {
            $connection->close();
        }*/
    }

    /**
     * This will receive any UnSubscription requests for this topic.
     *
     * @param ConnectionInterface $connection
     * @param Topic               $topic
     * @param WampRequest         $request
     */
    public function onUnSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
    {
    }

    /**
     * @param Topic        $topic
     * @param WampRequest  $request
     * @param array|string $data
     * @param string       $provider The name of pusher who push the data
     */
    public function onPush(Topic $topic, WampRequest $request, $data, $provider)
    {
        $newNotification = true;
        $topic->broadcast($newNotification);
    }

    /**
     * This will receive any Publish requests for this topic.
     *
     * @param ConnectionInterface $connection
     * @param Topic              $topic
     * @param WampRequest $request
     * @param $event
     * @param array $exclude
     * @param array $eligible
     *
     * @return mixed|void
     */
    public function onPublish(ConnectionInterface $connection, Topic $topic, WampRequest $request, $event, array $exclude, array $eligible)
    {
        $topic->broadcast([
            'msg' => $event,
        ]);
    }

    /**
     * Like RPC is will use to prefix the channel.
     *
     * @return string
     */
    public function getName()
    {
        return 'course.notification.topic';
    }
}
