<script>
  document.getElementById("addUserForm")
.addEventListener("submit", async (e) => {
    e.preventDefault();

    const res = await fetch("user_create.php", {
        method: "POST",
        body: new FormData(e.target)
    });

    const data = await res.json();

    if (data.status === "success") {
        location.reload();
    } else {
        alert(data.message);
    }
});
</script>