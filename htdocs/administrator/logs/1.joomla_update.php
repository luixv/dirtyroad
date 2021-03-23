#
#<?php die('Forbidden.'); ?>
#Date: 2021-03-04 16:44:04 UTC
#Software: Joomla Platform 13.1.0 Stable [ Curiosity ] 24-Apr-2013 00:00 GMT

#Fields: datetime	priority clientip	category	message
2021-03-04T16:44:04+00:00	INFO 2003:f0:b71a:200:ece2:cb4d:2193:1f7e	update	Update started by user Super User (658). Old version is 3.9.14.
2021-03-04T16:44:07+00:00	INFO 2003:f0:b71a:200:ece2:cb4d:2193:1f7e	update	Downloading update file from https://s3-us-west-2.amazonaws.com/joomla-official-downloads/joomladownloads/joomla3/Joomla_3.9.25-Stable-Update_Package.zip?X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIA6LXDJLNUINX2AVMH%2F20210304%2Fus-west-2%2Fs3%2Faws4_request&X-Amz-Date=20210304T164406Z&X-Amz-Expires=60&X-Amz-SignedHeaders=host&X-Amz-Signature=3ae8ec9c311aa6cd06ea3dbab7325cd6af341a81b4fccf899d4f7055e90b131a.
2021-03-04T16:44:20+00:00	INFO 2003:f0:b71a:200:ece2:cb4d:2193:1f7e	update	File Joomla_3.9.25-Stable-Update_Package.zip downloaded.
2021-03-04T16:44:21+00:00	INFO 2003:f0:b71a:200:ece2:cb4d:2193:1f7e	update	Starting installation of new version.
2021-03-04T16:44:59+00:00	INFO 2003:f0:b71a:200:ece2:cb4d:2193:1f7e	update	Finalising installation.
2021-03-04T16:45:00+00:00	INFO 2003:f0:b71a:200:ece2:cb4d:2193:1f7e	update	Ran query from file 3.9.16-2020-02-15. Query text: ALTER TABLE `#__categories` MODIFY `description` mediumtext;.
2021-03-04T16:45:00+00:00	INFO 2003:f0:b71a:200:ece2:cb4d:2193:1f7e	update	Ran query from file 3.9.16-2020-02-15. Query text: ALTER TABLE `#__categories` MODIFY `params` text;.
2021-03-04T16:45:00+00:00	INFO 2003:f0:b71a:200:ece2:cb4d:2193:1f7e	update	Ran query from file 3.9.16-2020-02-15. Query text: ALTER TABLE `#__fields` MODIFY `default_value` text;.
2021-03-04T16:45:00+00:00	INFO 2003:f0:b71a:200:ece2:cb4d:2193:1f7e	update	Ran query from file 3.9.16-2020-02-15. Query text: ALTER TABLE `#__fields_values` MODIFY `value` text;.
2021-03-04T16:45:00+00:00	INFO 2003:f0:b71a:200:ece2:cb4d:2193:1f7e	update	Ran query from file 3.9.16-2020-02-15. Query text: ALTER TABLE `#__finder_links` MODIFY `description` text;.
2021-03-04T16:45:00+00:00	INFO 2003:f0:b71a:200:ece2:cb4d:2193:1f7e	update	Ran query from file 3.9.16-2020-02-15. Query text: ALTER TABLE `#__modules` MODIFY `content` text;.
2021-03-04T16:45:01+00:00	INFO 2003:f0:b71a:200:ece2:cb4d:2193:1f7e	update	Ran query from file 3.9.16-2020-02-15. Query text: ALTER TABLE `#__ucm_content` MODIFY `core_body` mediumtext;.
2021-03-04T16:45:01+00:00	INFO 2003:f0:b71a:200:ece2:cb4d:2193:1f7e	update	Ran query from file 3.9.16-2020-02-15. Query text: ALTER TABLE `#__ucm_content` MODIFY `core_params` text;.
2021-03-04T16:45:01+00:00	INFO 2003:f0:b71a:200:ece2:cb4d:2193:1f7e	update	Ran query from file 3.9.16-2020-02-15. Query text: ALTER TABLE `#__ucm_content` MODIFY `core_images` text;.
2021-03-04T16:45:01+00:00	INFO 2003:f0:b71a:200:ece2:cb4d:2193:1f7e	update	Ran query from file 3.9.16-2020-02-15. Query text: ALTER TABLE `#__ucm_content` MODIFY `core_urls` text;.
2021-03-04T16:45:01+00:00	INFO 2003:f0:b71a:200:ece2:cb4d:2193:1f7e	update	Ran query from file 3.9.16-2020-02-15. Query text: ALTER TABLE `#__ucm_content` MODIFY `core_metakey` text;.
2021-03-04T16:45:01+00:00	INFO 2003:f0:b71a:200:ece2:cb4d:2193:1f7e	update	Ran query from file 3.9.16-2020-02-15. Query text: ALTER TABLE `#__ucm_content` MODIFY `core_metadesc` text;.
2021-03-04T16:45:01+00:00	INFO 2003:f0:b71a:200:ece2:cb4d:2193:1f7e	update	Ran query from file 3.9.16-2020-03-04. Query text: ALTER TABLE `#__users` DROP INDEX `username`;.
2021-03-04T16:45:01+00:00	INFO 2003:f0:b71a:200:ece2:cb4d:2193:1f7e	update	Ran query from file 3.9.16-2020-03-04. Query text: ALTER TABLE `#__users` ADD UNIQUE INDEX `idx_username` (`username`);.
2021-03-04T16:45:01+00:00	INFO 2003:f0:b71a:200:ece2:cb4d:2193:1f7e	update	Ran query from file 3.9.19-2020-05-16. Query text: ALTER TABLE `#__ucm_content` MODIFY `core_title` varchar(400) NOT NULL DEFAULT '.
2021-03-04T16:45:01+00:00	INFO 2003:f0:b71a:200:ece2:cb4d:2193:1f7e	update	Ran query from file 3.9.19-2020-06-01. Query text: INSERT INTO `#__postinstall_messages` (`extension_id`, `title_key`, `description.
2021-03-04T16:45:02+00:00	INFO 2003:f0:b71a:200:ece2:cb4d:2193:1f7e	update	Ran query from file 3.9.21-2020-08-02. Query text: INSERT INTO `#__postinstall_messages` (`extension_id`, `title_key`, `description.
2021-03-04T16:45:02+00:00	INFO 2003:f0:b71a:200:ece2:cb4d:2193:1f7e	update	Ran query from file 3.9.22-2020-09-16. Query text: INSERT INTO `#__postinstall_messages` (`extension_id`, `title_key`, `description.
2021-03-04T16:45:02+00:00	INFO 2003:f0:b71a:200:ece2:cb4d:2193:1f7e	update	Deleting removed files and folders.
2021-03-04T16:45:08+00:00	INFO 2003:f0:b71a:200:ece2:cb4d:2193:1f7e	update	Cleaning up after installation.
2021-03-04T16:45:08+00:00	INFO 2003:f0:b71a:200:ece2:cb4d:2193:1f7e	update	Update to version 3.9.25 is complete.
2021-03-07T17:36:04+00:00	INFO 46.128.35.31	update	Update started by user admin (42). Old version is 3.9.24.
2021-03-07T17:36:07+00:00	INFO 46.128.35.31	update	Downloading update file from https://s3-us-west-2.amazonaws.com/joomla-official-downloads/joomladownloads/joomla3/Joomla_3.9.25-Stable-Update_Package.zip?X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIA6LXDJLNUINX2AVMH%2F20210307%2Fus-west-2%2Fs3%2Faws4_request&X-Amz-Date=20210307T173605Z&X-Amz-Expires=60&X-Amz-SignedHeaders=host&X-Amz-Signature=49d75fca2f29045de08f36d256378f00176643c1dea776d373d9279ef8638b20.
2021-03-07T17:36:20+00:00	INFO 46.128.35.31	update	File Joomla_3.9.25-Stable-Update_Package.zip downloaded.
2021-03-07T17:36:21+00:00	INFO 46.128.35.31	update	Starting installation of new version.
2021-03-07T17:36:53+00:00	INFO 46.128.35.31	update	Finalising installation.
2021-03-07T17:36:53+00:00	INFO 46.128.35.31	update	Deleting removed files and folders.
2021-03-07T17:36:57+00:00	INFO 46.128.35.31	update	Cleaning up after installation.
2021-03-07T17:36:57+00:00	INFO 46.128.35.31	update	Update to version 3.9.25 is complete.
