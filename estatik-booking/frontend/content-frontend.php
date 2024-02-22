<!-- Template Frontend -->

<div class="ebwp-container">
    <div class="ebwp-container__dates">
        <?php if (isset($formatted_date_start) && !empty($formatted_date_start)): ?>
            <p>
                <?php echo __('Start date and time', 'ebwp'); ?>:
                <?php echo $formatted_date_start; ?>
            </p>
        <?php endif; ?>
        <?php if (isset($formatted_date_end) && !empty($formatted_date_end)): ?>
            <p>
                <?php echo __('End date and time', 'ebwp'); ?>:
                <?php echo $formatted_date_end; ?>
            </p>
        <?php endif; ?>
    </div>
    <div class="ebwp-container__address">
        <?php if (isset($address) && !empty($address)): ?>
            <p>
                <?php echo __('Address', 'ebwp'); ?>:
                <?php echo $address; ?>
            </p>
        <?php endif; ?>
        <?php if (isset($encoded_address) && !empty($encoded_address)): ?>
            <div>
                <iframe width="600" height="450" frameborder="0" style="border:0"
                    src="https://www.google.com/maps/embed/v1/place?q=<?php echo $encoded_address; ?>&key=<?php echo $google_map_api_key; ?>"
                    allowfullscreen></iframe>
            </div>
        <?php endif; ?>
    </div>
</div>