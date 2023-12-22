<ul class="flex flex-row mx-5 md:flex-row px-4">

    <?php if(auth()->guard()->check()): ?>
        
    <li>
        <a href="/add/student" class="block py-2 pr-4 pl-3 mx-5"> Add New </a>
    </li>
    
    <li>
        <form action="/logout" method="post">
        <?php echo csrf_field(); ?>
        <button class="block py-2 pr-4 pl-3 mx-5" style="cursor: pointer; transition: 0.5s"> Log Out </button>
        </form>
        
    </li>

    <?php else: ?>
    <li>
        <a href="/register" class="block py-2 pr-4 pl-3 mx-5"> Register </a>
    </li>
    
    <li>
        <a href="/login" class="block py-2 pr-4 pl-3 mx-5"> Log In </a>
    </li>
    <?php endif; ?>
</ul>
<?php /**PATH C:\Users\KING J MEDIA\Desktop\MY FIRST LARAVEL APP\example-app\resources\views/components/items.blade.php ENDPATH**/ ?>