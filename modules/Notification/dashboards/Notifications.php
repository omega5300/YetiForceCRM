<?php

/**
 * Notifications Dashboard Class.
 *
 * @copyright YetiForce Sp. z o.o
 * @license YetiForce Public License 3.0 (licenses/LicenseEN.txt or yetiforce.com)
 * @author Mariusz Krzaczkowski <m.krzaczkowski@yetiforce.com>
 */
class Notification_Notifications_Dashboard extends Vtiger_IndexAjax_View
{
	public function process(\App\Request $request)
	{
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		$widget = Vtiger_Widget_Model::getInstance($request->getInteger('linkid'), $currentUser->getId());
		$limit = (int) $widget->get('limit');
		if (empty($limit)) {
			$limit = 10;
		}
		$type = $request->get('type');
		$condition = false;
		if (!empty($type)) {
			$condition = ['u_#__notification.notification_type' => $type];
		}
		$notificationModel = Notification_Module_Model::getInstance($moduleName);
		$notifications = $notificationModel->getEntries($limit, $condition);

		$typesNotification = $notificationModel->getTypes();
		array_unshift($typesNotification, \App\Language::translate('All'));
		$viewer->assign('TYPES_NOTIFICATION', $typesNotification);
		$viewer->assign('NOTIFICATIONS', $notifications);
		$viewer->assign('WIDGET', $widget);
		$viewer->assign('MODULE_NAME', $moduleName);
		if ($request->has('content')) {
			$viewer->view('dashboards/NotificationsContents.tpl', $moduleName);
		} else {
			$viewer->view('dashboards/Notifications.tpl', $moduleName);
		}
	}
}
