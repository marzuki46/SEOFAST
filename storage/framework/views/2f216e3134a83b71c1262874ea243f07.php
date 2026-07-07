<div class="space-y-10">
    <div class="p-6 rounded-2xl border border-slate-200 bg-white shadow-sm">
        <h3 class="font-outfit font-bold text-slate-900 mb-4 text-sm tracking-wider uppercase">Search articles</h3>
        <form action="<?php echo e(route('blog.index')); ?>" method="GET" class="relative">
            <input type="text" name="q" value="<?php echo e(request('q')); ?>" placeholder="Search topics, keywords..."
                class="w-full bg-slate-50 border border-slate-200 focus:border-brand-indigo rounded-xl px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:outline-none focus:ring-1 focus:ring-brand-indigo transition-all">
            <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </button>
        </form>
    </div>

    <div class="p-6 rounded-2xl border border-slate-200 bg-white shadow-sm">
        <h3 class="font-outfit font-bold text-slate-900 mb-4 text-sm tracking-wider uppercase">Categories</h3>
        <div class="space-y-2">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('blog.category', $cat->slug)); ?>" class="flex justify-between items-center text-sm text-slate-600 hover:text-slate-900 hover:bg-slate-50 px-3 py-2 rounded-xl transition-all <?php echo e(isset($activeCategory) && $cat->id === $activeCategory->id ? 'text-slate-900 bg-slate-50 font-semibold border-l-2 border-brand-indigo pl-2' : ''); ?>">
                    <span><?php echo e($cat->silo_name); ?></span>
                    <span class="px-2 py-0.5 rounded-full bg-slate-100 border border-slate-200 text-slate-600 text-xs font-mono">
                        <?php echo e($cat->contents_count); ?>

                    </span>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <div class="p-6 rounded-2xl border border-slate-200 bg-white shadow-sm">
        <h3 class="font-outfit font-bold text-slate-900 mb-4 text-sm tracking-wider uppercase">Recent posts</h3>
        <div class="space-y-4">
            <?php $__currentLoopData = $recentPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex flex-col gap-1">
                    <span class="text-[10px] text-slate-500 font-mono uppercase"><?php echo e($recent->published_at ? $recent->published_at->format('M d, Y') : ''); ?></span>
                    <a href="<?php echo e(route('blog.show', $recent->slug)); ?>" class="text-sm text-slate-700 hover:text-brand-indigo font-medium line-clamp-2 transition-colors">
                        <?php echo e($recent->title); ?>

                    </a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<?php /**PATH D:\Program Marzuki\Projek Framework\SEOFAST\resources\views/blog/partials/sidebar.blade.php ENDPATH**/ ?>