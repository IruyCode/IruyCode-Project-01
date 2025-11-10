<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('debtManager', () => ({
            expandedIds: [],
            activeActionId: null,
            actionType: null,

            toggleInstallments(id) {
                if (this.expandedIds.includes(id)) {
                    this.expandedIds = this.expandedIds.filter(x => x !== id);
                } else {
                    this.expandedIds.push(id);
                }
            },

            isExpanded(id) {
                return this.expandedIds.includes(id);
            },

            // === Ações ===
            openDeleteDebtorsModal(id) {
                this.activeActionId = id;
                this.actionType = 'delete';
            },

            openEditDebtorsModal(id) {
                this.activeActionId = id;
                this.actionType = 'edit';
            },

            openFinishDebtorsModal(id) {
                this.activeActionId = id;
                this.actionType = 'finish';
            },

            closeModal() {
                this.actionType = null;
                this.activeActionId = null;
            },
        }));
    });
</script>
