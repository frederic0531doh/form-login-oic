<?php
require __DIR__ . '/includes/auth.php';

$pageTitle = 'Partenaires';
$activePage = 'partners';

$partnerError = $_SESSION['partner_error'] ?? null;
$partnerSuccess = $_SESSION['partner_success'] ?? null;
unset($_SESSION['partner_error'], $_SESSION['partner_success']);

$partners = $db->query(
    "SELECT * FROM partners WHERE id_user = ? ORDER BY company_name ASC",
    [$_SESSION['user_id']]
)->fetchAll(PDO::FETCH_ASSOC);

require __DIR__ . '/includes/layout_top.php';
?>

<div class="card mb-4">
    <div class="card-header d-flex align-items-center">
        <h3 class="card-title mb-0">
            <i class="bi bi-people me-2"></i>Partenaires
        </h3>
        <button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#addPartnerModal">
            <i class="bi bi-person-plus me-1"></i> Ajouter un partenaire
        </button>
    </div>
    <div class="card-body">
        <?php if ($partnerSuccess): ?>
            <div class="alert alert-success"><?= htmlspecialchars($partnerSuccess) ?></div>
        <?php endif; ?>
        <?php if ($partnerError): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($partnerError) ?></div>
        <?php endif; ?>

        <?php if (empty($partners)): ?>
            <p class="text-secondary mb-0">Aucun partenaire n'a encore été enregistré.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Raison sociale</th>
                            <th>Contact</th>
                            <th>Adresse mail</th>
                            <th>Ajouté le</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($partners as $partner): ?>
                            <tr>
                                <td>
                                    <i class="bi bi-building me-2 text-secondary"></i>
                                    <?= htmlspecialchars($partner['company_name']) ?>
                                </td>
                                <td><?= htmlspecialchars($partner['contact']) ?></td>
                                <td><?= htmlspecialchars($partner['email']) ?></td>
                                <td><?= htmlspecialchars(date('d/m/Y', strtotime($partner['created_at']))) ?></td>
                                <td class="text-end">
                                    <div class="d-flex gap-2 justify-content-end flex-wrap">
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#addDocumentModal"
                                            data-partner-id="<?= (int) $partner['id_partner'] ?>"
                                            data-partner-company="<?= htmlspecialchars($partner['company_name']) ?>">
                                            <i class="bi bi-file-earmark-plus me-1"></i> Ajouter un document
                                        </button>
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-secondary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editPartnerModal"
                                            data-partner-id="<?= (int) $partner['id_partner'] ?>"
                                            data-partner-company="<?= htmlspecialchars($partner['company_name']) ?>"
                                            data-partner-contact="<?= htmlspecialchars($partner['contact']) ?>"
                                            data-partner-email="<?= htmlspecialchars($partner['email']) ?>">
                                            <i class="bi bi-pencil me-1"></i> Modifier
                                        </button>
                                        <form method="post" action="controllers/delete_partner.php" onsubmit="return confirm('Supprimer définitivement ce partenaire ?');">
                                            <input type="hidden" name="id" value="<?= (int) $partner['id_partner'] ?>" />
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

<!--begin::Add Partner Modal-->
<div class="modal fade" id="addPartnerModal" tabindex="-1" aria-labelledby="addPartnerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="controllers/add_partner.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPartnerModalLabel">Ajouter un partenaire</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="add_company_name" class="form-label">Raison sociale</label>
                        <input type="text" class="form-control" id="add_company_name" name="company_name" maxlength="150" required />
                    </div>
                    <div class="mb-3">
                        <label for="add_contact" class="form-label">Contact</label>
                        <input type="text" class="form-control" id="add_contact" name="contact" maxlength="150" required />
                    </div>
                    <div class="mb-3">
                        <label for="add_email" class="form-label">Adresse mail</label>
                        <input type="email" class="form-control" id="add_email" name="email" maxlength="150" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-person-plus me-1"></i> Ajouter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end::Add Partner Modal-->

<!--begin::Edit Partner Modal-->
<div class="modal fade" id="editPartnerModal" tabindex="-1" aria-labelledby="editPartnerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="controllers/update_partner.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPartnerModalLabel">Modifier le partenaire</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_partner_id" value="" />
                    <div class="mb-3">
                        <label for="edit_company_name" class="form-label">Raison sociale</label>
                        <input type="text" class="form-control" id="edit_company_name" name="company_name" maxlength="150" required />
                    </div>
                    <div class="mb-3">
                        <label for="edit_contact" class="form-label">Contact</label>
                        <input type="text" class="form-control" id="edit_contact" name="contact" maxlength="150" required />
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Adresse mail</label>
                        <input type="email" class="form-control" id="edit_email" name="email" maxlength="150" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<!--end::Edit Partner Modal-->

<!--begin::Add Document Modal-->
<div class="modal fade" id="addDocumentModal" tabindex="-1" aria-labelledby="addDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="controllers/upload_document.php" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDocumentModalLabel">Ajouter un document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_partner" id="add_document_partner_id" value="" />
                    <p class="mb-3">
                        Partenaire : <strong id="add_document_partner_name"></strong>
                    </p>
                    <div class="mb-3">
                        <label for="add_document_name" class="form-label">Nom du document</label>
                        <input type="text" class="form-control" id="add_document_name" name="document_name" maxlength="150" placeholder="Ex : Statuts de l'entreprise" required />
                    </div>
                    <div class="mb-3">
                        <label for="add_document_file" class="form-label">Fichier</label>
                        <input type="file" class="form-control" id="add_document_file" name="document" required />
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
<!--end::Add Document Modal-->

<script>
    (() => {
        'use strict';
        const modal = document.getElementById('editPartnerModal');
        const idInput = document.getElementById('edit_partner_id');
        const companyInput = document.getElementById('edit_company_name');
        const contactInput = document.getElementById('edit_contact');
        const emailInput = document.getElementById('edit_email');

        modal.addEventListener('show.bs.modal', (event) => {
            const trigger = event.relatedTarget;
            idInput.value = trigger.dataset.partnerId;
            companyInput.value = trigger.dataset.partnerCompany || '';
            contactInput.value = trigger.dataset.partnerContact || '';
            emailInput.value = trigger.dataset.partnerEmail || '';
        });
    })();

    (() => {
        'use strict';
        const modal = document.getElementById('addDocumentModal');
        const idInput = document.getElementById('add_document_partner_id');
        const nameLabel = document.getElementById('add_document_partner_name');

        modal.addEventListener('show.bs.modal', (event) => {
            const trigger = event.relatedTarget;
            idInput.value = trigger.dataset.partnerId;
            nameLabel.textContent = trigger.dataset.partnerCompany || '';
        });
    })();
</script>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
