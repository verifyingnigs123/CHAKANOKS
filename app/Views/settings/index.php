<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
$page_title = 'System Settings';
$title = 'Settings';
?>

<div class="mb-4">
    <h4>System Settings</h4>
    <p class="text-muted">Configure system-wide settings and preferences</p>
</div>

<form method="post" action="<?= base_url('settings/update') ?>">
    <?= csrf_field() ?>
    
    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0">General Settings</h5>
        </div>
        <div class="card-body">
            <?php 
            $settingsArray = [];
            foreach ($settings as $setting) {
                $settingsArray[$setting['key']] = $setting;
            }
            ?>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>System Name</label>
                    <input type="text" name="settings[system_name]" class="form-control" value="<?= esc($settingsArray['system_name']['value'] ?? '') ?>">
                    <small class="text-muted"><?= esc($settingsArray['system_name']['description'] ?? '') ?></small>
                </div>
                <div class="col-md-6">
                    <label>Company Name</label>
                    <input type="text" name="settings[company_name]" class="form-control" value="<?= esc($settingsArray['company_name']['value'] ?? '') ?>">
                    <small class="text-muted"><?= esc($settingsArray['company_name']['description'] ?? '') ?></small>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Company Email</label>
                    <input type="email" name="settings[company_email]" class="form-control" value="<?= esc($settingsArray['company_email']['value'] ?? '') ?>">
                    <small class="text-muted"><?= esc($settingsArray['company_email']['description'] ?? '') ?></small>
                </div>
                <div class="col-md-6">
                    <label>Company Phone</label>
                    <input type="text" name="settings[company_phone]" class="form-control" value="<?= esc($settingsArray['company_phone']['value'] ?? '') ?>">
                    <small class="text-muted"><?= esc($settingsArray['company_phone']['description'] ?? '') ?></small>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0">Financial Settings</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4">
                    <label>Tax Rate (%)</label>
                    <input type="number" name="settings[tax_rate]" class="form-control" step="0.01" min="0" max="100" value="<?= esc($settingsArray['tax_rate']['value'] ?? '12') ?>">
                    <small class="text-muted"><?= esc($settingsArray['tax_rate']['description'] ?? '') ?></small>
                </div>
                <div class="col-md-4">
                    <label>Currency Code</label>
                    <input type="text" name="settings[currency]" class="form-control" value="<?= esc($settingsArray['currency']['value'] ?? 'PHP') ?>">
                    <small class="text-muted"><?= esc($settingsArray['currency']['description'] ?? '') ?></small>
                </div>
                <div class="col-md-4">
                    <label>Currency Symbol</label>
                    <input type="text" name="settings[currency_symbol]" class="form-control" value="<?= esc($settingsArray['currency_symbol']['value'] ?? '₱') ?>">
                    <small class="text-muted"><?= esc($settingsArray['currency_symbol']['description'] ?? '') ?></small>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <h5 class="mb-0">System Preferences</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="settings[low_stock_alert]" value="1" id="low_stock_alert" <?= ($settingsArray['low_stock_alert']['value'] ?? '1') == '1' ? 'checked' : '' ?>>
                        <label class="form-check-label" for="low_stock_alert">
                            Enable Low Stock Alerts
                        </label>
                        <small class="text-muted d-block"><?= esc($settingsArray['low_stock_alert']['description'] ?? '') ?></small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="settings[auto_approve_purchase_requests]" value="1" id="auto_approve" <?= ($settingsArray['auto_approve_purchase_requests']['value'] ?? '0') == '1' ? 'checked' : '' ?>>
                        <label class="form-check-label" for="auto_approve">
                            Auto-Approve Purchase Requests
                        </label>
                        <small class="text-muted d-block"><?= esc($settingsArray['auto_approve_purchase_requests']['description'] ?? '') ?></small>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label>Items Per Page</label>
                    <input type="number" name="settings[items_per_page]" class="form-control" min="10" max="100" value="<?= esc($settingsArray['items_per_page']['value'] ?? '20') ?>">
                    <small class="text-muted"><?= esc($settingsArray['items_per_page']['description'] ?? '') ?></small>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-end">
        <a href="<?= base_url('dashboard') ?>" class="btn btn-secondary me-2">Cancel</a>
        <button type="submit" class="btn btn-primary">Save Settings</button>
    </div>
</form>

<?= $this->endSection() ?>

