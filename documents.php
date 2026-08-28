<?php
require __DIR__ . '/includes/auth.php';
require __DIR__ . '/includes/functions.php';

$pageTitle = 'Documents';
$activePage = 'documents';

$uploadError = $_SESSION['upload_error'] ?? null;
$uploadSuccess = $_SESSION['upload_success'] ?? null;
unset($_SESSION['upload_error'], $_SESSION['upload_success']);

$documents = $db->query(
    "SELECT * FROM documents WHERE id_user = ? ORDER BY uploaded_at DESC",
    [$_SESSION['user_id']]
)->fetchAll(PDO::FETCH_ASSOC);

require __DIR__ . '/includes/layout_top.php';
?>

<div class="card mb-4">
    <div class="card-header d-flex align-items-center">
        <h3 class="card-title mb-0">
            <i class="bi bi-folder me-2"></i>Documents de <?= htmlspecialchars($user['company_name']) ?>
        </h3>
        <button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#uploadDocumentModal">
            <i class="bi bi-upload me-1"></i> Ajouter un document
        </button>
    </div>
    <div class="card-body">
        <?php if ($uploadSuccess): ?>
            <div class="alert alert-success"><?= htmlspecialchars($uploadSuccess) ?></div>
        <?php endif; ?>
        <?php if ($uploadError): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($uploadError) ?></div>
        <?php endif; ?>

        <?php if (empty($documents)): ?>
            <p class="text-secondary mb-0">Aucun document n'a encore été déposé.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Document</th>
                            <th>Taille</th>
                            <th>Déposé le</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($documents as $document): ?>
                            <tr>
                                <td>
                                    <i class="bi bi-file-earmark-text me-2 text-secondary"></i>
                                    <?= htmlspecialchars($document['original_name']) ?>
                                </td>
                                <td><?= htmlspecialchars(format_file_size((int) $document['file_size'])) ?></td>
                                <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($document['uploaded_at']))) ?></td>
                                <td class="text-end">
                                    <div class="d-flex gap-2 justify-content-end flex-wrap">
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#viewDocumentModal"
                                            data-doc-url="controllers/view_document.php?id=<?= (int) $document['id_document'] ?>"
                                            data-doc-name="<?= htmlspecialchars($document['original_name']) ?>">
                                            <i class="bi bi-eye me-1"></i> Consulter
                                        </button>
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-secondary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#replaceDocumentModal"
                                            data-doc-id="<?= (int) $document['id_document'] ?>"
                                            data-doc-name="<?= htmlspecialchars(pathinfo($document['original_name'], PATHINFO_FILENAME)) ?>">
                                            <i class="bi bi-arrow-repeat me-1"></i> Remplacer
                                        </button>
                                        <form method="post" action="controllers/delete_document.php" onsubmit="return confirm('Supprimer définitivement ce document ?');">
                                            <input type="hidden" name="id" value="<?= (int) $document['id_document'] ?>" />
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash me-1"></i> Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!--begin::Upload Document Modal-->
<div class="modal fade" id="uploadDocumentModal" tabindex="-1" aria-labelledby="uploadDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="controllers/upload_document.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadDocumentModalLabel">Ajouter un document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="document_name" class="form-label">Nom du document</label>
                        <input type="text" class="form-control" id="document_name" name="document_name" maxlength="150" placeholder="Ex : Statuts de l'entreprise" required />
                    </div>
                    <div class="mb-3">
                        <label for="document" class="form-label">Fichier</label>
                        <input type="file" class="form-control" id="document" name="document" required />
                        <div class="form-text">
                            Formats acceptés : PDF, Word, Excel, PowerPoint, images, texte, archive ZIP. Taille maximale : 10 Mo.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload me-1"></i> Envoyer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end::Upload Document Modal-->

<!--begin::View Document Modal-->
<div class="modal fade" id="viewDocumentModal" tabindex="-1" aria-labelledby="viewDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewDocumentModalLabel">Consulter le document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="viewDocumentFrame" src="" title="Aperçu du document" style="width: 100%; height: 75vh; border: 0;"></iframe>
            </div>
        </div>
    </div>
</div>
<!--end::View Document Modal-->

<!--begin::Replace Document Modal-->
<div class="modal fade" id="replaceDocumentModal" tabindex="-1" aria-labelledby="replaceDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="controllers/replace_document.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="replaceDocumentModalLabel">Remplacer le document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="replace_document_id" value="" />
                    <div class="mb-3">
                        <label for="replace_document_name" class="form-label">Nom du document</label>
                        <input type="text" class="form-control" id="replace_document_name" name="document_name" maxlength="150" required />
                    </div>
                    <div class="mb-3">
                        <label for="replace_document" class="form-label">Nouveau fichier</label>
                        <input type="file" class="form-control" id="replace_document" name="document" required />
                        <div class="form-text">
                            Le fichier actuel sera définitivement remplacé. Taille maximale : 10 Mo.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-arrow-repeat me-1"></i> Remplacer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end::Replace Document Modal-->

<script>
    (() => {
        'use strict';
        const modal = document.getElementById('viewDocumentModal');
        const frame = document.getElementById('viewDocumentFrame');
        const label = document.getElementById('viewDocumentModalLabel');

        modal.addEventListener('show.bs.modal', (event) => {
            const trigger = event.relatedTarget;
            frame.src = trigger.dataset.docUrl;
            label.textContent = trigger.dataset.docName || 'Document';
        });

        modal.addEventListener('hidden.bs.modal', () => {
            frame.src = '';
        });
    })();

    (() => {
        'use strict';
        const modal = document.getElementById('replaceDocumentModal');
        const idInput = document.getElementById('replace_document_id');
        const nameInput = document.getElementById('replace_document_name');

        modal.addEventListener('show.bs.modal', (event) => {
            const trigger = event.relatedTarget;
            idInput.value = trigger.dataset.docId;
            nameInput.value = trigger.dataset.docName || '';
        });
    })();
</script>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>