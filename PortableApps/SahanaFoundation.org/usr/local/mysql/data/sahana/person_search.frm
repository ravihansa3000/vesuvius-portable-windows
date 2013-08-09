TYPE=VIEW
query=select `pu`.`p_uuid` AS `p_uuid`,`pu`.`full_name` AS `full_name`,`pu`.`given_name` AS `given_name`,`pu`.`family_name` AS `family_name`,`pu`.`expiry_date` AS `expiry_date`,`ps`.`last_updated` AS `updated`,`ps`.`last_updated_db` AS `updated_db`,(case when (`ps`.`opt_status` not in (_utf8\'ali\',_utf8\'mis\',_utf8\'inj\',_utf8\'dec\',_utf8\'unk\',_utf8\'fnd\')) then _utf8\'unk\' else `ps`.`opt_status` end) AS `opt_status`,(case when ((`pd`.`opt_gender` not in (_utf8\'mal\',_utf8\'fml\')) or isnull(`pd`.`opt_gender`)) then _utf8\'unk\' else `pd`.`opt_gender` end) AS `opt_gender`,(case when isnull(cast(`pd`.`years_old` as unsigned)) then -(1) else `pd`.`years_old` end) AS `years_old`,(case when isnull(cast(`pd`.`minAge` as unsigned)) then -(1) else `pd`.`minAge` end) AS `minAge`,(case when isnull(cast(`pd`.`maxAge` as unsigned)) then -(1) else `pd`.`maxAge` end) AS `maxAge`,(case when (cast(`pd`.`years_old` as unsigned) is not null) then (case when (`pd`.`years_old` < 18) then _utf8\'youth\' when (`pd`.`years_old` >= 18) then _utf8\'adult\' end) when ((cast(`pd`.`minAge` as unsigned) is not null) and (cast(`pd`.`maxAge` as unsigned) is not null) and (`pd`.`minAge` < 18) and (`pd`.`maxAge` >= 18)) then _utf8\'both\' when ((cast(`pd`.`minAge` as unsigned) is not null) and (`pd`.`minAge` >= 18)) then _utf8\'adult\' when ((cast(`pd`.`maxAge` as unsigned) is not null) and (`pd`.`maxAge` < 18)) then _utf8\'youth\' else _utf8\'unknown\' end) AS `ageGroup`,`i`.`image_height` AS `image_height`,`i`.`image_width` AS `image_width`,`i`.`url_thumb` AS `url_thumb`,(case when (`h`.`hospital_uuid` = -(1)) then NULL else `h`.`icon_url` end) AS `icon_url`,`inc`.`shortname` AS `shortname`,(case when ((`pu`.`hospital_uuid` not in (1,2,3)) or isnull(`pu`.`hospital_uuid`)) then _utf8\'public\' else lcase(`h`.`short_name`) end) AS `hospital`,`pd`.`other_comments` AS `comments`,`pd`.`last_seen` AS `last_seen`,`ecl`.`person_id` AS `mass_casualty_id` from ((((((`sahana`.`person_uuid` `pu` join `sahana`.`person_status` `ps` on((`pu`.`p_uuid` = `ps`.`p_uuid`))) left join `sahana`.`image` `i` on(((`pu`.`p_uuid` = `i`.`p_uuid`) and (`i`.`principal` = 1)))) join `sahana`.`person_details` `pd` on((`pu`.`p_uuid` = `pd`.`p_uuid`))) join `sahana`.`incident` `inc` on((`inc`.`incident_id` = `pu`.`incident_id`))) left join `sahana`.`hospital` `h` on((`h`.`hospital_uuid` = `pu`.`hospital_uuid`))) left join `sahana`.`edxl_co_lpf` `ecl` on((`ecl`.`p_uuid` = `pu`.`p_uuid`)))
md5=bd8e985b95f5d22ab0993c0f0f2fcb19
updatable=0
algorithm=0
definer_user=sahana
definer_host=localhost
suid=1
with_check_option=0
timestamp=2013-08-09 20:58:22
create-version=1
source=select `pu`.`p_uuid` AS `p_uuid`,`pu`.`full_name` AS `full_name`,`pu`.`given_name` AS `given_name`,`pu`.`family_name` AS `family_name`,`pu`.`expiry_date` AS `expiry_date`,`ps`.`last_updated` AS `updated`,`ps`.`last_updated_db` AS `updated_db`,(case when (`ps`.`opt_status` not in (_utf8\'ali\',_utf8\'mis\',_utf8\'inj\',_utf8\'dec\',_utf8\'unk\',_utf8\'fnd\')) then _utf8\'unk\' else `ps`.`opt_status` end) AS `opt_status`,(case when ((`pd`.`opt_gender` not in (_utf8\'mal\',_utf8\'fml\')) or isnull(`pd`.`opt_gender`)) then _utf8\'unk\' else `pd`.`opt_gender` end) AS `opt_gender`,(case when isnull(cast(`pd`.`years_old` as unsigned)) then -(1) else `pd`.`years_old` end) AS `years_old`,(case when isnull(cast(`pd`.`minAge` as unsigned)) then -(1) else `pd`.`minAge` end) AS `minAge`,(case when isnull(cast(`pd`.`maxAge` as unsigned)) then -(1) else `pd`.`maxAge` end) AS `maxAge`,(case when (cast(`pd`.`years_old` as unsigned) is not null) then (case when (`pd`.`years_old` < 18) then _utf8\'youth\' when (`pd`.`years_old` >= 18) then _utf8\'adult\' end) when ((cast(`pd`.`minAge` as unsigned) is not null) and (cast(`pd`.`maxAge` as unsigned) is not null) and (`pd`.`minAge` < 18) and (`pd`.`maxAge` >= 18)) then _utf8\'both\' when ((cast(`pd`.`minAge` as unsigned) is not null) and (`pd`.`minAge` >= 18)) then _utf8\'adult\' when ((cast(`pd`.`maxAge` as unsigned) is not null) and (`pd`.`maxAge` < 18)) then _utf8\'youth\' else _utf8\'unknown\' end) AS `ageGroup`,`i`.`image_height` AS `image_height`,`i`.`image_width` AS `image_width`,`i`.`url_thumb` AS `url_thumb`,(case when (`h`.`hospital_uuid` = -(1)) then NULL else `h`.`icon_url` end) AS `icon_url`,`inc`.`shortname` AS `shortname`,(case when ((`pu`.`hospital_uuid` not in (1,2,3)) or isnull(`pu`.`hospital_uuid`)) then _utf8\'public\' else lcase(`h`.`short_name`) end) AS `hospital`,`pd`.`other_comments` AS `comments`,`pd`.`last_seen` AS `last_seen`,`ecl`.`person_id` AS `mass_casualty_id` from ((((((`person_uuid` `pu` join `person_status` `ps` on((`pu`.`p_uuid` = `ps`.`p_uuid`))) left join `image` `i` on(((`pu`.`p_uuid` = `i`.`p_uuid`) and (`i`.`principal` = 1)))) join `person_details` `pd` on((`pu`.`p_uuid` = `pd`.`p_uuid`))) join `incident` `inc` on((`inc`.`incident_id` = `pu`.`incident_id`))) left join `hospital` `h` on((`h`.`hospital_uuid` = `pu`.`hospital_uuid`))) left join `edxl_co_lpf` `ecl` on((`ecl`.`p_uuid` = `pu`.`p_uuid`)))
client_cs_name=utf8
connection_cl_name=utf8_general_ci
view_body_utf8=select `pu`.`p_uuid` AS `p_uuid`,`pu`.`full_name` AS `full_name`,`pu`.`given_name` AS `given_name`,`pu`.`family_name` AS `family_name`,`pu`.`expiry_date` AS `expiry_date`,`ps`.`last_updated` AS `updated`,`ps`.`last_updated_db` AS `updated_db`,(case when (`ps`.`opt_status` not in (\'ali\',\'mis\',\'inj\',\'dec\',\'unk\',\'fnd\')) then \'unk\' else `ps`.`opt_status` end) AS `opt_status`,(case when ((`pd`.`opt_gender` not in (\'mal\',\'fml\')) or isnull(`pd`.`opt_gender`)) then \'unk\' else `pd`.`opt_gender` end) AS `opt_gender`,(case when isnull(cast(`pd`.`years_old` as unsigned)) then -(1) else `pd`.`years_old` end) AS `years_old`,(case when isnull(cast(`pd`.`minAge` as unsigned)) then -(1) else `pd`.`minAge` end) AS `minAge`,(case when isnull(cast(`pd`.`maxAge` as unsigned)) then -(1) else `pd`.`maxAge` end) AS `maxAge`,(case when (cast(`pd`.`years_old` as unsigned) is not null) then (case when (`pd`.`years_old` < 18) then \'youth\' when (`pd`.`years_old` >= 18) then \'adult\' end) when ((cast(`pd`.`minAge` as unsigned) is not null) and (cast(`pd`.`maxAge` as unsigned) is not null) and (`pd`.`minAge` < 18) and (`pd`.`maxAge` >= 18)) then \'both\' when ((cast(`pd`.`minAge` as unsigned) is not null) and (`pd`.`minAge` >= 18)) then \'adult\' when ((cast(`pd`.`maxAge` as unsigned) is not null) and (`pd`.`maxAge` < 18)) then \'youth\' else \'unknown\' end) AS `ageGroup`,`i`.`image_height` AS `image_height`,`i`.`image_width` AS `image_width`,`i`.`url_thumb` AS `url_thumb`,(case when (`h`.`hospital_uuid` = -(1)) then NULL else `h`.`icon_url` end) AS `icon_url`,`inc`.`shortname` AS `shortname`,(case when ((`pu`.`hospital_uuid` not in (1,2,3)) or isnull(`pu`.`hospital_uuid`)) then \'public\' else lcase(`h`.`short_name`) end) AS `hospital`,`pd`.`other_comments` AS `comments`,`pd`.`last_seen` AS `last_seen`,`ecl`.`person_id` AS `mass_casualty_id` from ((((((`sahana`.`person_uuid` `pu` join `sahana`.`person_status` `ps` on((`pu`.`p_uuid` = `ps`.`p_uuid`))) left join `sahana`.`image` `i` on(((`pu`.`p_uuid` = `i`.`p_uuid`) and (`i`.`principal` = 1)))) join `sahana`.`person_details` `pd` on((`pu`.`p_uuid` = `pd`.`p_uuid`))) join `sahana`.`incident` `inc` on((`inc`.`incident_id` = `pu`.`incident_id`))) left join `sahana`.`hospital` `h` on((`h`.`hospital_uuid` = `pu`.`hospital_uuid`))) left join `sahana`.`edxl_co_lpf` `ecl` on((`ecl`.`p_uuid` = `pu`.`p_uuid`)))
