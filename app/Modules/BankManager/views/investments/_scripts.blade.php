 <script>
     document.addEventListener('alpine:init', () => {
         Alpine.data('InvestmentManager', () => ({
             expandedDebts: [],
             installmentsToPay: 1,

             init() {},

             toggleInstallments(InvestmentId) {
                 if (this.expandedDebts.includes(InvestmentId)) {
                     this.expandedDebts = this.expandedDebts.filter(id => id !== InvestmentId);
                 } else {
                     this.expandedDebts.push(InvestmentId);
                 }
             },

             isExpanded(InvestmentId) {
                 return this.expandedDebts.includes(InvestmentId);
             },
         }));
     });
 </script>
