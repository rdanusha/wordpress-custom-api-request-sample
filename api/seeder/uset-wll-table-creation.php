<?php

function e25_create_location_types_table()
{
    global $wpdb;

    $prefix = "e25_";
    $table_name = $prefix . "location_types";
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))$charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function e25_create_towns_table()
{
    global $wpdb;

    $prefix = "e25_";
    $table_name = $prefix . "towns";
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))$charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function e25_create_locations_table()
{
    global $wpdb;

    $prefix = "e25_";
    $table_name = $prefix . "locations";
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
  `id` INT NOT NULL AUTO_INCREMENT,
  `location_type_id` INT NOT NULL,
  `town_id` INT NOT NULL,
  `group_id` INT NULL,
  `location_title` VARCHAR(300) NULL,
  `location_desc` TEXT NULL,
  `water_led_limit` VARCHAR(15) NULL,
  `latitude` VARCHAR(15) NULL,
  `longitude` VARCHAR(15) NULL,
  `first_name` VARCHAR(45) NULL,
  `last_name` VARCHAR(45) NULL,
  `telephone` VARCHAR(20) NULL,
  `email` VARCHAR(30) NULL,
  `created_at` DATETIME NULL,
  `created_by` INT NULL,
  `updated_at` DATETIME NULL,
  `updated_by` INT NULL,
  INDEX `fk_locations_location_types1_idx` (`location_type_id` ASC) VISIBLE,
  INDEX `fk_locations_towns1_idx` (`town_id` ASC) VISIBLE,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_locations_location_types1`
    FOREIGN KEY (`location_type_id`)
    REFERENCES `{$prefix}location_types` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_locations_towns1`
    FOREIGN KEY (`town_id`)
    REFERENCES `{$prefix}towns` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)$charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function e25_create_taps_table()
{
    global $wpdb;

    $prefix = "e25_";
    $table_name = $prefix . "taps";
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
  `id` INT NOT NULL AUTO_INCREMENT,
  `location_id` INT NOT NULL,
  `tap_detail` VARCHAR(300) NULL,
  `first_draw_lab_id` VARCHAR(45) NULL,
  `flush_lab_id` VARCHAR(45) NULL,
  `date_sampled` DATE NULL,
  `fixture_states` VARCHAR(15) NULL,
  `created_at` DATETIME NULL,
  `created_by` INT NULL,
  `updated_at` DATETIME NULL,
  `updated_by` INT NULL,
  `is_synced` TINYINT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_taps_locations1_idx` (`location_id` ASC) VISIBLE,
  CONSTRAINT `fk_taps_locations1`
    FOREIGN KEY (`location_id`)
    REFERENCES `{$prefix}locations` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)$charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function e25_create_planned_actions_table()
{
    global $wpdb;

    $prefix = "e25_";
    $table_name = $prefix . "planned_actions";
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
  `id` INT NOT NULL AUTO_INCREMENT,
  `action` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))$charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function e25_create_immediate_actions_table()
{
    global $wpdb;

    $prefix = "e25_";
    $table_name = $prefix . "immediate_actions";
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
 `id` INT NOT NULL AUTO_INCREMENT,
  `action` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))$charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function e25_create_permanent_actions_table()
{
    global $wpdb;

    $prefix = "e25_";
    $table_name = $prefix . "permanent_actions";
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
  `id` INT NOT NULL AUTO_INCREMENT,
  `action` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))$charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function e25_create_lead_data_table()
{
    global $wpdb;

    $prefix = "e25_";
    $table_name = $prefix . "lead_data";
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
  `id` INT NOT NULL AUTO_INCREMENT,
  `tap_id` INT NOT NULL,
  `first_draw_lead_amount` VARCHAR(45) NULL,
  `flush_lead_amount` VARCHAR(45) NULL,
  `date_analyzed` DATE NULL,
  `immediate_action_date` DATE NULL,
  `permanent_action_date` DATE NULL,
  `permanent_actions_id` INT NULL,
  `immediate_actions_id` INT NULL,
  `planned_actions_id` INT NULL,
  `created_at` DATETIME NULL,
  `created_by` INT NULL,
  `updated_at` DATETIME NULL,
  `updated_by` INT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_lead_data_taps1_idx` (`tap_id` ASC) VISIBLE,
  INDEX `fk_lead_data_permanent_actions1_idx` (`permanent_actions_id` ASC) VISIBLE,
  INDEX `fk_lead_data_immediate_actions1_idx` (`immediate_actions_id` ASC) VISIBLE,
  INDEX `fk_lead_data_planned_actions1_idx` (`planned_actions_id` ASC) VISIBLE,
  CONSTRAINT `fk_lead_data_taps1`
    FOREIGN KEY (`tap_id`)
    REFERENCES `{$prefix}taps` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lead_data_permanent_actions1`
    FOREIGN KEY ()
    REFERENCES `{$prefix}permanent_actions` ()
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lead_data_immediate_actions1`
    FOREIGN KEY ()
    REFERENCES `{$prefix}immediate_actions` ()
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_lead_data_planned_actions1`
    FOREIGN KEY ()
    REFERENCES `{$prefix}planned_actions` ()
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)$charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}


function e25_create_locations_view_table()
{
    global $wpdb;

    $prefix = "e25_";
    $table_name = $prefix . "lead_data_view";
    $lead_table_name = $prefix . "lead_data";
    $taps_table_name = $prefix . "taps";

    $sql = "CREATE VIEW $table_name AS SELECT
    `id`,
    `tap_id`,
    `location_id`,
    `tap_detail`,
    `lab_id`, 
    `date_sampled`,
    `amount`,
    `date_analyzed`,
    `type`
    FROM ( 
        SELECT LD.id, T.id AS tap_id, LD.first_draw_lead_amount AS amount, LD.date_analyzed, 'first_draw_lead_amount' AS type, 
        T.first_draw_lab_id AS lab_id, T.tap_detail, T.date_sampled, T.location_id
        FROM $lead_table_name LD
            JOIN $taps_table_name T ON T.id = LD.tap_id
        UNION
        SELECT LD.id, T.id AS tap_id, LD.flush_lead_amount AS amount, LD.date_analyzed, 'flush_lead_amount' AS type,
        T.flush_lab_id AS lab_id, T.tap_detail, T.date_sampled, T.location_id
        FROM $lead_table_name LD
            JOIN $taps_table_name T ON T.id = LD.tap_id
    )
    AS lead_data";
    $wpdb->query($sql);
}

function e25_execute()
{
    e25_create_location_types_table();
    e25_create_towns_table();
    e25_create_locations_table();
    e25_create_taps_table();
    e25_create_planned_actions_table();
    e25_create_immediate_actions_table();
    e25_create_permanent_actions_table();
    e25_create_lead_data_table();
    e25_create_locations_view_table();
    echo "Database tables created successfully";
}
e25_execute();
