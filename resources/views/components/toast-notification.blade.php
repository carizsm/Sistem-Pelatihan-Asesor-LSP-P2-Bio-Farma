{{-- Toast Notification Component --}}
<div x-data="toastManager()" 
     x-init="@if(session('success')) showToast('{{ session('success') }}', 'success') @endif
            @if(session('error')) showToast('{{ session('error') }}', 'error') @endif
            @if(session('info')) showToast('{{ session('info') }}', 'info') @endif"
     class="fixed top-4 right-4 z-50 space-y-2">
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.show"
             x-transition:enter="transform ease-out duration-300"
             x-transition:enter-start="translate-x-full opacity-0"
             x-transition:enter-end="translate-x-0 opacity-100"
             x-transition:leave="transform ease-in duration-200"
             x-transition:leave-start="translate-x-0 opacity-100"
             x-transition:leave-end="translate-x-full opacity-0"
             class="flex items-start gap-3 p-4 rounded-lg shadow-lg max-w-md"
             :class="{
                 'bg-green-100 border-l-4 border-green-500': toast.type === 'success',
                 'bg-red-100 border-l-4 border-red-500': toast.type === 'error',
                 'bg-blue-100 border-l-4 border-blue-500': toast.type === 'info'
             }">
            <div class="flex-shrink-0">
                <svg x-show="toast.type === 'success'" class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <svg x-show="toast.type === 'error'" class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <svg x-show="toast.type === 'info'" class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="flex-1">
                <p class="text-sm font-semibold" 
                   :class="{
                       'text-green-800': toast.type === 'success',
                       'text-red-800': toast.type === 'error',
                       'text-blue-800': toast.type === 'info'
                   }" 
                   x-text="toast.message"></p>
            </div>
            <button @click="removeToast(toast.id)" class="flex-shrink-0 text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    </template>
</div>

<script>
    if (typeof toastManager === 'undefined') {
        function toastManager() {
            return {
                toasts: [],
                nextId: 1,
                
                showToast(message, type = 'info') {
                    const id = this.nextId++;
                    const toast = { id, message, type, show: true };
                    this.toasts.push(toast);
                    
                    setTimeout(() => {
                        this.removeToast(id);
                    }, 5000);
                },
                
                removeToast(id) {
                    const index = this.toasts.findIndex(t => t.id === id);
                    if (index > -1) {
                        this.toasts[index].show = false;
                        setTimeout(() => {
                            this.toasts.splice(index, 1);
                        }, 300);
                    }
                }
            }
        }
    }
</script>
