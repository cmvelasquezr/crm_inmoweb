document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("affinity-modal");
  const content = document.getElementById("affinity-content");
  const close = document.getElementById("close-modal");

  document.querySelectorAll(".view-affinity").forEach(button => {
    button.addEventListener("click", async () => {
      const id = button.dataset.id;
      const response = await fetch('src/Controllers/Ajax/affinityProperty.php?id=${id}');
      const html = await response.text();
      content.innerHTML = html;
      modal.style.display = "block";
    });
  });

  close.addEventListener("click", () => {
    modal.style.display = "none";
    content.innerHTML = "";
  });
});
