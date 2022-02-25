<?php
/**
* Name: Domain Pricing
* Description: A table of domain extensions and prices.
*/
$domains = modules::run('domains/domain_pricing', '');
?>
<table class="table table-striped table-bordered AppendDataTables">
<thead><tr><th><?=lang('extension')?></th><th><?=lang('registration')?></th><th><?=lang('transfer')?></th></tr></thead>
<tbody>
    <?php foreach(Item::get_domains() as $domain) { ?>
        <tr>
            <td><?=$domain->item_name?></td>
            <td><?=Applib::format_currency(config_item('default_currency'), $domain->registration)?></td>
            <td><?=Applib::format_currency(config_item('default_currency'), $domain->transfer)?></td>
        </tr>
    <?php } ?>
</tbody>
</table>