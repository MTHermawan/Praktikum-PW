function tambahSoal(soalCount) {
    const container = document.getElementById('soal-container');
    const div = document.createElement('div');
    div.className = 'soal-group';
    jawabanCount[soalCount] = 1;

    div.innerHTML = `
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <label for="soal">Soal ${soalCount + 1}:</label>
        <button type="button" onclick="hapusSoal(this)" class="btn-danger">Hapus Soal</button>
    </div>
    <textarea name="soal[]" class="input-text" rows="4" required></textarea>

    <div class="jawaban-container" style="display: flex; flex-direction: column; gap: 4px;">
        <h4>Jawaban:</h4>
        <div class="jawaban-input">
            <div style="display: flex; align-items: center; gap: 10px;">
                <input type="text" name="jawaban[${soalCount}][]" class="input-text" required>
                    <label style="display: flex; gap: 4px;"><input type="checkbox"
                        name="correct_answer[${soalCount}][]" value="0">Benar</label>
                    <button type="button" onclick="hapusJawaban(this)" class="btn-danger">Hapus</button>
            </div>
        </div>
    </div>
    <button type="button" onclick="tambahJawaban(${soalCount}, ${soalCount})" class="btn-secondary">Tambah Jawaban</button>
    `;
    container.appendChild(div);
    soalCount++;
}

function tambahJawaban(soalIndex, soalCount) {
    if (!jawabanCount[soalIndex]) {
        jawabanCount[soalIndex] = 1;
    }

    const container = document.querySelector(`#soal-container .soal-group:nth-child(${soalIndex + 1}) .jawaban-container`);
    const div = document.createElement('div');
    div.className = 'jawaban-input';
    div.style.maxWidth = '500px';
    div.innerHTML = `
    <div style="display: flex; align-items: center; gap: 10px;">
        <input type="text" name="jawaban[${soalCount}][]" class="input-text" required>
            <label style="display: flex; gap: 4px;"><input type="checkbox"
                name="correct_answer[${soalCount}][]" value="0">Benar</label>
            <button type="button" onclick="hapusJawaban(this)" class="btn-danger">Hapus</button>
    </div>
    `;
    container.appendChild(div);
    jawabanCount[soalIndex]++;
}

function hapusSoal(button) {
    button.closest('.soal-group').remove();
    // Update soal numbers
    document.querySelectorAll('.soal-group').forEach((soal, index) => {
        soal.querySelector('label').textContent = `Soal ${index + 1}:`;
    });
    soalCount--;
}

function hapusJawaban(button) {
    button.closest('.jawaban-input').remove();
}