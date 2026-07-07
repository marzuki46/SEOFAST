<?php $__env->startSection('title', 'URL Structure Audit - SEOFAST'); ?>
<?php $__env->startSection('page_title', 'URL Structure Audit'); ?>

<?php $__env->startSection('admin_content'); ?>
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div class="flex items-center gap-4">
        <p class="text-slate-500 text-sm">Analyze URL depth, slug quality, and keyword presence across your content.</p>
        <?php if($summary): ?>
        <div class="flex items-center gap-2 text-sm font-semibold">
            <span class="px-3 py-1 bg-slate-100 rounded-lg text-slate-600">Avg: <strong><?php echo e($summary['avg_score']); ?></strong></span>
            <span class="px-3 py-1 bg-emerald-100 rounded-lg text-emerald-700">Good: <?php echo e($summary['good']); ?></span>
            <span class="px-3 py-1 bg-amber-100 rounded-lg text-amber-700">Needs Work: <?php echo e($summary['needs_work']); ?></span>
            <span class="px-3 py-1 bg-red-100 rounded-lg text-red-700">Poor: <?php echo e($summary['poor']); ?></span>
            <span class="px-3 py-1 bg-slate-200 rounded-lg text-slate-500">Issues: <?php echo e($summary['total_issues']); ?></span>
        </div>
        <?php endif; ?>
    </div>
    <div class="flex items-center gap-3">
        <form action="<?php echo e(route('admin.url-audit.run')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-bold text-sm hover:bg-indigo-500 transition shadow-sm">Run Audit</button>
        </form>
    </div>
</div>

<?php if(session('success')): ?>
    <div class="mb-4 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-lg text-sm">
        <?php echo session('success'); ?>

    </div>
<?php endif; ?>

<?php if(!$summary): ?>
<div class="bg-white border rounded-xl shadow-sm p-12 text-center text-slate-500">
    <p class="text-lg font-semibold mb-1">No audit data yet</p>
    <p class="text-sm">Click "Run Audit" to analyze your content URLs.</p>
</div>
<?php else: ?>
<!-- Filters -->
<div class="mb-4 flex items-center gap-2 text-sm flex-wrap">
    <a href="<?php echo e(route('admin.url-audit.index', ['filter' => 'good', 'search' => $search])); ?>" class="px-4 py-2 rounded-lg font-semibold transition <?php echo e($filter === 'good' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'); ?>">Good</a>
    <a href="<?php echo e(route('admin.url-audit.index', ['filter' => 'needs_work', 'search' => $search])); ?>" class="px-4 py-2 rounded-lg font-semibold transition <?php echo e($filter === 'needs_work' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'); ?>">Needs Work</a>
    <a href="<?php echo e(route('admin.url-audit.index', ['filter' => 'poor', 'search' => $search])); ?>" class="px-4 py-2 rounded-lg font-semibold transition <?php echo e($filter === 'poor' ? 'bg-red-100 text-red-800' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'); ?>">Poor</a>
    <a href="<?php echo e(route('admin.url-audit.index')); ?>" class="px-4 py-2 rounded-lg font-semibold transition <?php echo e(!$filter ? 'bg-slate-200 text-slate-800' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'); ?>">All</a>

    <div class="text-sm text-slate-400 ml-2"><?php echo e($total); ?> URLs</div>

    <form method="GET" action="<?php echo e(route('admin.url-audit.index')); ?>" class="flex items-center gap-2 ml-auto">
        <?php if($filter): ?> <input type="hidden" name="filter" value="<?php echo e($filter); ?>"> <?php endif; ?>
        <input type="text" name="search" value="<?php echo e($search); ?>" placeholder="Search title or slug..." class="px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 w-56">
        <button type="submit" class="px-3 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-50">Search</button>
    </form>
</div>

<div class="bg-white border rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Content</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Slug</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Depth</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Length</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">KW in URL</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Issues</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase">Score</th>
                <th class="px-6 py-3 text-xs font-semibold text-gray-500 uppercase text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <?php $__empty_1 = true; $__currentLoopData = $paginated; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <?php
                $scoreClass = $r['score'] >= 80 ? 'text-emerald-600' : ($r['score'] >= 50 ? 'text-amber-600' : 'text-red-600');
                $barColor = $r['score'] >= 80 ? 'bg-emerald-500' : ($r['score'] >= 50 ? 'bg-amber-500' : 'bg-red-500');
            ?>
            <tr>
                <td class="px-6 py-4 text-sm">
                    <a href="<?php echo e(route('admin.content.show', $r['content_id'])); ?>" class="font-medium text-indigo-600 hover:text-indigo-900"><?php echo e(Str::limit($r['title'], 50)); ?></a>
                    <?php if($r['target_keyword']): ?>
                        <p class="text-xs text-slate-400 mt-0.5">KW: <?php echo e($r['target_keyword']); ?></p>
                    <?php endif; ?>
                </td>
                <td class="px-6 py-4 text-sm font-mono text-slate-600 max-w-xs truncate"><?php echo e($r['slug']); ?></td>
                <td class="px-6 py-4 text-sm text-center <?php echo e($r['depth'] > 3 ? 'text-red-500 font-bold' : 'text-slate-600'); ?>"><?php echo e($r['depth']); ?></td>
                <td class="px-6 py-4 text-sm text-center <?php echo e($r['slug_length'] > 60 ? 'text-red-500 font-bold' : 'text-slate-600'); ?>"><?php echo e($r['slug_length']); ?></td>
                <td class="px-6 py-4 text-sm text-center">
                    <?php if($r['keyword_in_url'] === true): ?>
                        <span class="text-emerald-500 font-bold">Yes</span>
                    <?php elseif($r['keyword_in_url'] === false): ?>
                        <span class="text-red-500 font-bold">No</span>
                    <?php else: ?>
                        <span class="text-slate-400">N/A</span>
                    <?php endif; ?>
                </td>
                <td class="px-6 py-4 text-sm">
                    <?php if(count($r['issues']) > 0): ?>
                        <div class="flex flex-wrap gap-1">
                            <?php $__currentLoopData = array_slice($r['issues'], 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $issue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700"><?php echo e($issue); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if(count($r['issues']) > 3): ?>
                                <span class="text-xs text-slate-400">+<?php echo e(count($r['issues']) - 3); ?> more</span>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <span class="text-emerald-500 text-xs font-semibold">Clean</span>
                    <?php endif; ?>
                </td>
                <td class="px-6 py-4 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="font-bold <?php echo e($scoreClass); ?>"><?php echo e($r['score']); ?></span>
                        <div class="w-12 h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full <?php echo e($barColor); ?>" style="width: <?php echo e($r['score']); ?>%"></div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="<?php echo e(route('admin.content.edit', $r['content_id'])); ?>" class="text-sm text-amber-600 hover:text-amber-900 font-semibold">Edit</a>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="8" class="px-6 py-8 text-center text-gray-500">No URLs match the current filter.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="mt-4 flex items-center justify-between text-sm text-slate-500">
    <div>Showing <?php echo e(count($paginated)); ?> of <?php echo e($total); ?> URLs (Page <?php echo e($page); ?>)</div>
    <div class="flex gap-2">
        <?php if($page > 1): ?>
        <a href="<?php echo e(route('admin.url-audit.index', ['page' => $page - 1, 'filter' => $filter, 'search' => $search])); ?>" class="px-3 py-1.5 bg-white border rounded-lg hover:bg-slate-50 font-semibold">&larr; Previous</a>
        <?php endif; ?>
        <?php if(($page * $perPage) < $total): ?>
        <a href="<?php echo e(route('admin.url-audit.index', ['page' => $page + 1, 'filter' => $filter, 'search' => $search])); ?>" class="px-3 py-1.5 bg-white border rounded-lg hover:bg-slate-50 font-semibold">Next &rarr;</a>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Program Marzuki\Projek Framework\SEOFAST\resources\views/admin/url-audit/index.blade.php ENDPATH**/ ?>