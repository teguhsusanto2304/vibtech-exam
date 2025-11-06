<footer class="mt-auto py-4 border-t border-border-light dark:border-border-dark text-center text-sm text-text-light-secondary dark:text-text-dark-secondary">
    © {{ date('Y') }} Vibtech Genesis. All rights reserved.
</footer>
<div id="confirm-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4" aria-modal="true" role="dialog">
    
    <!-- Backdrop -->
    <div id="modal-backdrop" class="absolute inset-0 bg-gray-900/50 dark:bg-gray-900/80 transition-opacity"></div>
    
    <!-- Modal Content -->
    <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-sm mx-auto p-6 transform transition-all scale-95 opacity-0 duration-200">
        <div class="text-center">
            <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white" id="modal-title">Confirm Action</h3>
            <div class="mt-2">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    You are about to <strong id="modal-action-text">perform an irreversible action</strong>. 
                    Please confirm to proceed.
                </p>
            </div>
        </div>
        
        <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
            <button type="button" id="confirm-action" 
                    class="inline-flex w-full justify-center rounded-lg border border-transparent bg-red-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:col-start-2 sm:text-sm">
                Confirm
            </button>
            <button type="button" id="cancel-action"
                    class="mt-3 inline-flex w-full justify-center rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2 text-base font-medium text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:col-start-1 sm:mt-0 sm:text-sm">
                Cancel
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('confirm-modal');
        const confirmButton = document.getElementById('confirm-action');
        const cancelButton = document.getElementById('cancel-action');
        const actionTextElement = document.getElementById('modal-action-text');
        
        // Select all forms that require confirmation
        const formsNeedingConfirmation = document.querySelectorAll('.needs-confirmation');
        
        let formToSubmit = null; // Variable to hold the form element that was clicked

        // --- Modal Control Functions ---
        const showModal = (actionMessage) => {
            // Update the dynamic text based on the form's data-action attribute
            actionTextElement.textContent = actionMessage;
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            // Animate the content
            setTimeout(() => {
                const content = modal.querySelector('.relative');
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        };

        const hideModal = () => {
            const content = modal.querySelector('.relative');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
                formToSubmit = null; // Clear the form reference
            }, 200);
        };

        // --- Attach Listeners to ALL Forms ---
        formsNeedingConfirmation.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault(); 
                
                // 1. Store the reference to the currently submitting form
                formToSubmit = this;
                
                // 2. Get the confirmation message from the form's data attribute
                const actionMessage = this.getAttribute('data-action') || 'perform this action';
                
                // 3. Show the modal with the custom message
                showModal(actionMessage);
            });
        });

        // --- Modal Action Handlers ---

        // 1. Handle Confirm Button Click
        confirmButton.addEventListener('click', () => {
            if (formToSubmit) {
                // Programmatically submit the stored form
                formToSubmit.submit();
                
                // Hide the modal 
                hideModal();
            }
        });

        // 2. Handle Cancel/Close Button Clicks
        cancelButton.addEventListener('click', hideModal);
        document.getElementById('modal-backdrop').addEventListener('click', hideModal);
    });
</script>