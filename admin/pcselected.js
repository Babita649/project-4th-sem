// Load PC data from database
fetch("get_pcs.php")
    .then(res => res.json())
    .then(data => {
        const totalPCs = data.total;
        const pcsInUse = data.in_use;

        document.getElementById("pcsInUseCount").innerText = pcsInUse.length;
        document.getElementById("totalPcsCount").innerText = totalPCs;
        document.getElementById("occupiedLabel").innerText = pcsInUse.length;

        const grid = document.getElementById("pcGrid");
        grid.innerHTML = ""; // Clear old grid

        for (let i = 1; i <= totalPCs; i++) {
            const pc = document.createElement("div");
            pc.classList.add("pc");

            if (pcsInUse.includes(i)) {
                pc.classList.add("busy");
            } else {
                pc.classList.add("free");
            }

            grid.appendChild(pc);
        }

        // Popup functions
        document.getElementById("pcsInUseBox").onclick = () => {
            let list = pcsInUse.map(pc => `PC ${pc}`).join("<br>");
            openPopup("PCs Currently in Use", list);
        };

        document.getElementById("totalPcsBox").onclick = () => {
            let list = "";
            for (let i = 1; i <= totalPCs; i++) {
                list += `PC ${i} <br>`;
            }
            openPopup("All PCs in System", list);
        };

    })
    .catch(err => {
        console.error("Error loading data:", err);
    });

// Popup helpers
function openPopup(title, content) {
    document.getElementById("popupTitle").innerText = title;
    document.getElementById("popupContent").innerHTML = content;
    document.getElementById("popupBg").style.display = "flex";
}

function closePopup() {
    document.getElementById("popupBg").style.display = "none";
}
