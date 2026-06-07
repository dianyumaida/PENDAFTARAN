// Simulasi database awal (Data pendaftar yang sudah ada sebelumnya)
let databasePendaftaran = [
    { id: 1, no: "A01", angka: 100, hp: "081234567", alamat: "Jakarta Pusat", ket: "Pendaftar gelombang pertama" },
    { id: 2, no: "B02", angka: 200, hp: "085678910", alamat: "Bandung Kota", ket: "Pendaftar gelombang kedua" }
];

document.getElementById('formPendaftaran').addEventListener('submit', function(e) {
    e.preventDefault();

    // Ambil nilai dari kolom input form
    const txtNo = document.getElementById('inputNo').value.trim();
    const numAngka = parseInt(document.getElementById('inputAngka').value);
    const txtHp = document.getElementById('inputHp').value;
    const txtAlamat = document.getElementById('inputAlamat').value;
    const txtKet = document.getElementById('inputKet').value;

    const notifBox = document.getElementById('notifikasiArea');
    notifBox.innerHTML = ""; // Bersihkan pesan error sebelumnya
    notifBox.className = "hidden";

    // KONDISI 1: Cek apakah kombinasi 'No' DAN 'Angka' sudah terpakai bersamaan
    const cekDuplikatTotal = databasePendaftaran.find(data => data.no === txtNo && data.angka === numAngka);

    if (cekDuplikatTotal) {
        notifBox.className = "notif error";
        notifBox.innerHTML = `<strong>Pendaftaran Gagal!</strong> Kombinasi No (<strong>${txtNo}</strong>) dan Angka (<strong>${numAngka}</strong>) sudah terdaftar. Data ini terkunci dan tidak bisa diisi kembali.`;
        return; // Hentikan proses simpan data baru
    }

    // KONDISI 2: Cek apakah salah satu ('No' ATAU 'Angka') ada yang sama dengan data lama
    const cekKesamaanParsial = databasePendaftaran.filter(data => data.no === txtNo || data.angka === numAngka);

    if (cekKesamaanParsial.length > 0) {
        notifBox.className = "notif warning";
        let htmlKonten = `<strong>Pendaftaran Ditolak!</strong> Ditemukan kemiripan data pada sistem kami:<ul>`;
        
        cekKesamaanParsial.forEach(data => {
            let alasanSama = (data.no === txtNo) ? `No (${txtNo})` : `Angka (${numAngka})`;
            htmlKonten += `<li>Data ${alasanSama} sudah digunakan oleh pendaftar lain. 
                <button type="button" class="btn-detail" onclick="lihatDetail(${data.id})">Lihat Detail Data Lama</button>
            </li>`;
        });
        
        htmlKonten += `</ul>`;
        notifBox.innerHTML = htmlKonten;
        return; // Hentikan proses simpan data baru
    }

    // KONDISI 3: Jika lolos semua pengecekan, simpan data baru ke memori
    const idBaru = databasePendaftaran.length + 1;
    databasePendaftaran.push({
        id: idBaru, no: txtNo, angka: numAngka, hp: txtHp, alamat: txtAlamat, ket: txtKet
    });

    alert("Selamat! Pendaftaran Anda berhasil disimpan.");
    document.getElementById('formPendaftaran').reset();
});

// Fungsi untuk memunculkan detail data pendaftar lama melalui pop-up alert
function lihatDetail(id) {
    const dataPilihan = databasePendaftaran.find(d => d.id === id);
    if (dataPilihan) {
        alert(
            `==== DETAIL DATA LAMA ====\n\n` +
            `ID Pendaftaran: ${dataPilihan.id}\n` +
            `No / Kode: ${dataPilihan.no}\n` +
            `Angka: ${dataPilihan.angka}\n` +
            `No. HP: ${dataPilihan.hp}\n` +
            `Alamat: ${dataPilihan.alamat}\n` +
            `Keterangan: ${dataPilihan.ket}`
        );
    }
}
