

<?php $__env->startSection('title', 'Manage Leads'); ?>
<?php $__env->startSection('page-title', 'Manage Leads'); ?>

<?php $__env->startSection('page-actions'); ?>
<div class="btn-toolbar mb-2 mb-md-0">
    <div class="btn-group me-2">
        <a href="<?php echo e(route('client.leads.create')); ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>New Lead
        </a>
        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="bi bi-upload me-2"></i>Import
        </button>
        <a href="<?php echo e(route('client.leads.export', request()->query())); ?>" class="btn btn-outline-success">
            <i class="bi bi-download me-2"></i>Export
        </a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-primary"><?php echo e($stats['total']); ?></h5>
                <p class="card-text text-muted">Total</p>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-success"><?php echo e($stats['active']); ?></h5>
                <p class="card-text text-muted">Ativos</p>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-warning"><?php echo e($stats['pending']); ?></h5>
                <p class="card-text text-muted">Pendentes</p>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-info"><?php echo e($stats['contacted']); ?></h5>
                <p class="card-text text-muted">Contatados</p>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-success"><?php echo e($stats['converted']); ?></h5>
                <p class="card-text text-muted">Convertidos</p>
            </div>
        </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-secondary"><?php echo e($stats['inactive']); ?></h5>
                <p class="card-text text-muted">Inativos</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('client.leads.index')); ?>" class="row g-3">
            <div class="col-md-3">
                <label for="search" class="form-label">Buscar</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="<?php echo e(request('search')); ?>" placeholder="Nome, email ou telefone">
            </div>
            <div class="col-md-2">
                <label for="status" class="form-label">Status</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Todos</option>
                    <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Ativo</option>
                    <option value="inactive" <?php echo e(request('status') === 'inactive' ? 'selected' : ''); ?>>Inativo</option>
                    <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>Pendente</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="contacted" class="form-label">Contatado</label>
                <select class="form-select" id="contacted" name="contacted">
                    <option value="">Todos</option>
                    <option value="true" <?php echo e(request('contacted') === 'true' ? 'selected' : ''); ?>>Sim</option>
                    <option value="false" <?php echo e(request('contacted') === 'false' ? 'selected' : ''); ?>>Não</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="converted" class="form-label">Convertido</label>
                <select class="form-select" id="converted" name="converted">
                    <option value="">Todos</option>
                    <option value="true" <?php echo e(request('converted') === 'true' ? 'selected' : ''); ?>>Sim</option>
                    <option value="false" <?php echo e(request('converted') === 'false' ? 'selected' : ''); ?>>Não</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="sort_by" class="form-label">Ordenar por</label>
                <select class="form-select" id="sort_by" name="sort_by">
                    <option value="created_at" <?php echo e(request('sort_by') === 'created_at' ? 'selected' : ''); ?>>Data</option>
                    <option value="name" <?php echo e(request('sort_by') === 'name' ? 'selected' : ''); ?>>Nome</option>
                    <option value="status" <?php echo e(request('sort_by') === 'status' ? 'selected' : ''); ?>>Status</option>
                </select>
            </div>
            <div class="col-md-1">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Leads Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="bi bi-people me-2"></i>Leads List
        </h5>
    </div>
    <div class="card-body">
        <?php if($leads->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Origem</th>
                            <th>Status</th>
                            <th>Contatado</th>
                            <th>Convertido</th>
                            <th>Data</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $leads; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                        <span class="text-white fw-bold"><?php echo e(substr($lead->name, 0, 1)); ?></span>
                                    </div>
                                    <div>
                                        <h6 class="mb-0"><?php echo e($lead->name); ?></h6>
                                        <?php if($lead->notes): ?>
                                            <small class="text-muted"><?php echo e(Str::limit($lead->notes, 50)); ?></small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo e($lead->email); ?></td>
                            <td><?php echo e($lead->phone ?? '-'); ?></td>
                            <td>
                                <span class="badge bg-light text-dark"><?php echo e($lead->source); ?></span>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo e($lead->status_badge_color); ?>">
                                    <?php echo e($lead->status_badge_text); ?>

                                </span>
                            </td>
                            <td>
                                <?php if($lead->contacted): ?>
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Sim
                                    </span>
                                    <?php if($lead->contacted_at): ?>
                                        <br><small class="text-muted"><?php echo e($lead->contacted_at->format('d/m/Y')); ?></small>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Não</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($lead->converted): ?>
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Sim
                                    </span>
                                    <?php if($lead->conversion_value): ?>
                                        <br><small class="text-success">R$ <?php echo e(number_format($lead->conversion_value, 2, ',', '.')); ?></small>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Não</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div><?php echo e($lead->created_at->format('d/m/Y')); ?></div>
                                <small class="text-muted"><?php echo e($lead->created_at->format('H:i')); ?></small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="<?php echo e(route('client.leads.show', $lead)); ?>" 
                                       class="btn btn-sm btn-outline-primary" 
                                       data-bs-toggle="tooltip" title="Ver detalhes">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="<?php echo e(route('client.leads.edit', $lead)); ?>" 
                                       class="btn btn-sm btn-outline-secondary" 
                                       data-bs-toggle="tooltip" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                            onclick="deleteLead(<?php echo e($lead->id); ?>)" 
                                            data-bs-toggle="tooltip" title="Delete">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Mostrando <?php echo e($leads->firstItem()); ?> a <?php echo e($leads->lastItem()); ?> 
                    de <?php echo e($leads->total()); ?> resultados
                </div>
                <div>
                    <?php echo e($leads->links()); ?>

                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-people display-1 text-muted"></i>
                <h4 class="mt-3 text-muted">Nenhum lead encontrado</h4>
                <p class="text-muted">Comece adicionando seu primeiro lead ou importe uma lista.</p>
                <a href="<?php echo e(route('client.leads.create')); ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Add Lead
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-upload me-2"></i>Import Leads
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo e(route('client.leads.import')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="file" class="form-label">Arquivo CSV</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".csv,.txt" required>
                        <div class="form-text">
                            Formato esperado: Nome, Email, Telefone, Origem, Status, Notas
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Dica:</strong> O arquivo deve conter pelo menos as colunas Nome e Email.
                        Download do <a href="#" onclick="downloadTemplate()">modelo de template</a>.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload me-2"></i>Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle me-2"></i>Confirmar Exclusão
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja excluir este lead?</p>
                <p class="text-danger">
                    <i class="bi bi-warning me-2"></i>
                    Esta ação não pode ser desfeita.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-2"></i>Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function deleteLead(leadId) {
    const form = document.getElementById('deleteForm');
    form.action = `/client/leads/${leadId}`;
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function downloadTemplate() {
    const csvContent = "Nome,Email,Telefone,Origem,Status,Notas\nJoão Silva,joao@email.com,11999999999,website,active,Lead interessado\nMaria Santos,maria@email.com,11888888888,facebook,pending,Precisa de mais informações";
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'template_leads.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}

// Auto-submit form on filter change
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const selects = form.querySelectorAll('select');
    
    selects.forEach(select => {
        select.addEventListener('change', function() {
            form.submit();
        });
    });
});

// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.client', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\WORK-Station\freelance\workana\12\resources\views/client/leads/index.blade.php ENDPATH**/ ?>