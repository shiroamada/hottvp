// This file is specifically for the license list page.
// It handles the AJAX update for remarks and modal dismissal.

document.addEventListener('DOMContentLoaded', function () {
    console.log('license-list.js: DOMContentLoaded fired.');
    const updateRemarkModal = document.getElementById('kt_modal_update_remark');
    console.log('license-list.js: updateRemarkModal element:', updateRemarkModal);

    if (!updateRemarkModal) {
        console.error('license-list.js: Modal element #kt_modal_update_remark not found.');
        return;
    }

    const titleTemplate = updateRemarkModal.dataset.titleTemplate || 'Update Remark for Code: :code';
    console.log('Title Template from data attribute:', titleTemplate); // DEBUG
    const modalTitle = updateRemarkModal.querySelector('.kt-modal-title');
    const saveRemarkButton = updateRemarkModal.querySelector('#modal_save_remark_button');

    // Event listener for the "Update Remark" buttons
    document.querySelectorAll('.update-remark-button').forEach(button => {
        button.addEventListener('click', function () {
            console.log('license-list.js: Update Remark button clicked.');
            const codeId = this.getAttribute('data-id');
            const currentRemark = this.getAttribute('data-remark');

            const remarkInput = updateRemarkModal.querySelector('#modal_remark_input');
            const codeIdInput = updateRemarkModal.querySelector('#modal_code_id_input');

            if (modalTitle) {
                const newTitle = titleTemplate.replace(':code', codeId);
                console.log('Setting modal title to:', newTitle); // DEBUG
                modalTitle.textContent = newTitle;
            }
            if (remarkInput) remarkInput.value = currentRemark;
            if (codeIdInput) codeIdInput.value = codeId;

            // Manually show the Metronic modal and force z-index
            if (typeof KTModal !== 'undefined') {
                console.log('license-list.js: KTModal is defined.');
                let modalInstance = KTModal.getInstance(updateRemarkModal);
                if (!modalInstance) {
                    console.log('license-list.js: Creating new KTModal instance.');
                    modalInstance = new KTModal(updateRemarkModal);
                }
                console.log('license-list.js: Showing modal instance.', modalInstance);
                modalInstance.show();

                // Force a very high z-index for the modal and its backdrop
                updateRemarkModal.style.zIndex = '9999999'; // Even higher than phpdebugbar
                updateRemarkModal.style.position = 'fixed';
                updateRemarkModal.style.display = 'block';
                updateRemarkModal.style.opacity = '1';

                // Also try to force the backdrop z-index if it exists
                const backdrop = document.querySelector('.kt-modal-backdrop');
                if (backdrop) {
                    backdrop.style.zIndex = '9999998'; // One less than modal
                }

            } else {
                console.error('license-list.js: KTModal is NOT defined. Cannot show modal.');
                // Fallback for showing if KTModal is not available (though it should be)
                updateRemarkModal.classList.add('show');
                updateRemarkModal.style.display = 'block';
                updateRemarkModal.style.opacity = '1';
                updateRemarkModal.style.zIndex = '9999999';
                document.body.classList.add('modal-open');
            }
        });
    });

    // Attach click listener for the save button (only once)
    if (saveRemarkButton && !saveRemarkButton._hasClickListener) {
        saveRemarkButton.addEventListener('click', function () {
            console.log('license-list.js: Save remark button clicked.');
            const codeIdToUpdate = updateRemarkModal.querySelector('#modal_code_id_input').value;
            const newRemark = updateRemarkModal.querySelector('#modal_remark_input').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/admin/license/${codeIdToUpdate}/update`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ remark: newRemark })
            })
            .then(response => response.json())
            .then(data => {
                if (data.code === 0) { // Check data.code for success
                    toastr.success(data.msg); // Use data.msg for toast message
                    const remarkSpan = document.querySelector(`span[data-code-id="${codeIdToUpdate}"]`);
                    if (remarkSpan) {
                        remarkSpan.textContent = newRemark;
                        const updateButton = document.querySelector(`button[data-id="${codeIdToUpdate}"]`);
                        if (updateButton) {
                            updateButton.setAttribute('data-remark', newRemark);
                        }
                    }
                    // Programmatically dismiss Metronic modal
                    const dismissButton = updateRemarkModal.querySelector('[data-kt-modal-dismiss="true"]');
                    if (dismissButton) {
                        dismissButton.click();
                    }
                    // Reload the page after successful update
                    if (data.redirect) {
                        setTimeout(() => {
                            location.reload();
                        }, 1500); // Delay reload by 1.5 seconds
                    }
                } else {
                    toastr.error(data.message || 'Failed to update remark.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('An error occurred while updating the remark.');
            });
        });
        saveRemarkButton._hasClickListener = true; // Mark as having listener
    }
});
