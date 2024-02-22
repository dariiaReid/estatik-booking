<!-- Template meta box -->

<style>
    #ebwp_estatik_booking_meta_box {
        border-radius: 10px;
        border: 1px solid #ccc;
    }

    .ebwp-meta-box {
        padding: 20px;
    }

    .ebwp-meta-box__dates {
        display: flex;
        gap: 10px;
    }

    .ebwp-meta-box div {
        margin-bottom: 10px;
    }

    .ebwp-meta-box label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: #333;
    }

    .ebwp-meta-box input {
        width: auto;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .ebwp-meta-box input:focus {
        border-color: #00bfff;
        box-shadow: 0 0 5px rgba(0, 191, 255, 0.5);
    }

    #ebwp_address {
        width: 100%;
    }

    @media (max-width: 768px) {
        .ebwp-meta-box {
            display: flex;
            gap: 10px;
            padding: 20px;
            flex-direction: column;
        }
    }
</style>



<div class="ebwp-meta-box">
    <div class="ebwp-meta-box__dates">
        <div>
            <label for="ebwp_start_date">
                <?php _e('Start Date', 'ebwp'); ?>:
            </label>
            <input type="datetime-local" id="ebwp_start_date" name="ebwp_start_date"
                value="<?php if ($start_date_formatted) {
                    echo esc_attr($start_date_formatted);
                } ?>"
                class="datepicker">
        </div>
        <div>
            <label for="ebwp_end_date">
                <?php _e('End Date:', 'ebwp'); ?>
            </label>
            <input type="datetime-local" id="ebwp_end_date" name="ebwp_end_date"
                value="<?php if ($end_date_formatted) {
                    echo esc_attr($end_date_formatted);
                } ?>" class="datepicker">
        </div>
    </div>
    <div class="ebwp_input_address">
        <label for="ebwp_address">
            <?php _e('Address:', 'ebwp'); ?>
        </label>
        <input type="text" id="ebwp_address" name="ebwp_address" value="<?php echo esc_attr($address); ?>">
    </div>
</div>