<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <?php echo app('Illuminate\Foundation\Vite')('resources/js/app.js'); ?>
    <?php if (!isset($__inertiaSsrDispatched)) { $__inertiaSsrDispatched = true; $__inertiaSsrResponse = app(\Inertia\Ssr\Gateway::class)->dispatch($page); }  if ($__inertiaSsrResponse) { echo $__inertiaSsrResponse->head; } ?>
    <?php echo app('Tighten\Ziggy\BladeRouteGenerator')->generate(); ?>
     <!-- Tag untuk favicon -->
    <link rel="icon" href="<?php echo e(asset('images/kudajingkrak.png')); ?>" type="image/png" sizes="any">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jingkrak Autosport</title>
    <!-- Tag lain yang kamu butuhkan -->
</head>

<body>
    <?php if (!isset($__inertiaSsrDispatched)) { $__inertiaSsrDispatched = true; $__inertiaSsrResponse = app(\Inertia\Ssr\Gateway::class)->dispatch($page); }  if ($__inertiaSsrResponse) { echo $__inertiaSsrResponse->body; } else { ?><div id="app" data-page="<?php echo e(json_encode($page)); ?>"></div><?php } ?>
</body>

</html>
<?php /**PATH /Users/leevydmalik/Documents/Raflee/kuliah/SEMESTER - 6/ABP/BE - KUDAJINGKRAK/Laravel-Inertia-Vue-Starter/resources/views/app.blade.php ENDPATH**/ ?>