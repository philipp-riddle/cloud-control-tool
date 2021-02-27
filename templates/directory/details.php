<p>Found <?php echo \count($data['allFiles']); ?> files.</p>

<?php var_dump($data['dirFiles']); ?>

<?php foreach ($data['dirFiles'] as $dirFile): ?>
    <?php echo $dirFile; ?> <br>
<?php endforeach; ?>