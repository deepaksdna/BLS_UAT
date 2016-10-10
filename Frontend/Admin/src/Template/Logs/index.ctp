<nav class="large-3 medium-4 columns mbot15" id="actions-sidebar">
    <ul class="nav nav-pills links-admin">
        <li><?= $this->Html->link(__('Bulk Add'), ['controller'=>'Products', 'action' => 'bulkadd']) ?></li>
    </ul>
</nav>

<div class="large-9 medium-8 columns content col-xs-12">
   
    <div class="row">
	<h2>Products Logs</h2>
	 <table class="vertical-table">
        <tr>
            <th><?= __('Last Modified By : ') ?></th>
            <td><?= h(ucfirst($log->admin->firstname).' '.ucfirst($log->admin->lastname)) ?></td>
        </tr>
        <tr>
            <th><?= __('Last Modified @ : ') ?></th>
            <td><?= $log->modified ?></td>
        </tr>
    </table>
	
	 
        <?= $log->errors; ?>
    </div>
	
	
	
	
	<div class="row">
	<h2>Promotions Logs</h2>
	<table class="vertical-table">
        <tr>
            <th><?= __('Last Modified By : ') ?></th>
            <td><?= h(ucfirst($log2->admin->firstname).' '.ucfirst($log2->admin->lastname)) ?></td>
        </tr>
        <tr>
            <th><?= __('Last Modified @ : ') ?></th>
            <td><?= $log2->modified ?></td>
        </tr>
    </table>
	
        
		<?= $log2->errors; ?>
    </div>
</div>
