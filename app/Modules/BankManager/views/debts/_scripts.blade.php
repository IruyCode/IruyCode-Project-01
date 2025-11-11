  <script>
      document.addEventListener('alpine:init', () => {
          Alpine.data('debtManager', () => ({
              expandedDebts: [],
              installmentsToPay: 1,

              init() {
                  // Inicializações podem ser feitas aqui
              },

              toggleInstallments(debtId) {
                  if (this.expandedDebts.includes(debtId)) {
                      this.expandedDebts = this.expandedDebts.filter(id => id !== debtId);
                  } else {
                      this.expandedDebts.push(debtId);
                  }
              },

              isExpanded(debtId) {
                  return this.expandedDebts.includes(debtId);
              },

              markInstallmentAsPaid(installmentId) {
                  // Implemente a lógica para marcar parcela como paga
                  console.log('Marcar parcela como paga:', installmentId);
                  fetch(`/bank-manager/debts/${installmentId}/installments/mark-paid`, {
                          method: 'POST',
                          headers: {
                              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                  .content
                          }
                      })
                      .then(res => res.json())
                      .then(data => {
                          console.log(data.message);
                          window.location.reload();
                      });
              },

              deleteInstallment(installmentId) {
                  // Implemente a lógica para deletar parcela
                  console.log('Deletar parcela:', installmentId);

                  if (confirm('Tem certeza que deseja excluir esta parcela?')) {
                      fetch(`/bank-manager/installments/${installmentId}`, {
                              method: 'DELETE',
                              headers: {
                                  'X-CSRF-TOKEN': document.querySelector(
                                      'meta[name="csrf-token"]').content
                              }
                          })
                          .then(res => res.json())
                          .then(data => {
                              console.log(data.message);
                              window.location.reload();
                          });
                  }
              },

              payMultipleInstallments(debtId) {
                  // Implemente a lógica para pagar múltiplas parcelas
                  console.log(`Pagar ${this.installmentsToPay} parcelas da dívida:`, debtId);
                  fetch(`/bank-manager/debts/${debtId}/pay-multiples`, {
                          method: 'PUT',
                          headers: {
                              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                  .content,
                              'Content-Type': 'application/json'
                          },
                          body: JSON.stringify({
                              quantity: this.installmentsToPay
                          })
                      })
                      .then(res => res.json())
                      .then(data => {
                          console.log(data.message);
                          window.location.reload();
                      });
              }

          }));
      });
  </script>
