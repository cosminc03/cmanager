function changeUserNotificationNumber() {
    var $totalUserNotificationsArea = $('.user-notifications'),
        totalUserNotificationsNumber = parseInt($totalUserNotificationsArea.html().trim(), 10) + 1;

    $totalUserNotificationsArea.html(totalUserNotificationsNumber);
    if ($totalUserNotificationsArea.hasClass('hidden')) {
        $totalUserNotificationsArea.removeClass('hidden');
    }
}
