PROPOSAL TUGAS AKHIR Perancangan Website E-Commerce dengan Fitur Chatbot untuk
Kepuasan Pelanggan PT Agro Pangan Maju Menggunakan Waterfall
Oleh: Alvito Rafky Sumeri
1201226085
Dosen Pembimbing Proposal: Dewi Marini Umi Atmaja, S.Kom., M.Kom.
24960026
PROGRAM STUDI STRATA 1 SISTEM INFORMASI FAKULTAS REKAYASA INDUSTRI UNIVERSITAS TELKOM 2026
I

DAFTAR ISI
Bab I Pendahuluan ............................................................................................... 1 I.1 Latar Belakang ....................................................................................... 1 I.2 Perumusan Masalah................................................................................ 5 I.3 Tujuan Penelitian.................................................................................... 5 I.4 Batasan Penelitian .................................................................................. 5 I.5 Manfaat Penelitian.................................................................................. 6 I.6 Sitematika Penulisan .............................................................................. 6
Bab II landasan teori ............................................................................................. 9 II.1 Materi Pendukung Riset ......................................................................... 9 II.1.1 Sistem Informasi ............................................................................. 9 II.1.2 Website.......................................................................................... 10 II.1.3 Website E-Commerce ................................................................... 11 II.1.4 Chatbot .......................................................................................... 13 II.1.5 UML .............................................................................................. 17 II.1.6 Metode Waterfall .......................................................................... 24 II.1.7 User Acceptance Testing (UAT)................................................... 27 II.1.8 Blackbox Testing .......................................................................... 28 II.2 Penelitian Terdahulu............................................................................. 30 II.3 Analisis Penelitian Terdahulu .............................................................. 37
BAB III Metode Penelitian ................................................................................ 42 III.1 Metode Penelitian ............................................................................. 42 III.2 Sistematika Penyelesaian Masalah ................................................... 43 III.2.1 Identifikasi Masalah ...................................................................... 44 III.2.2 Pengumpulan Data ........................................................................ 44 III.2.3 Analisis Perencanaan Teknis ........................................................ 45
II

III.2.4 Perancangan Arsitektur Sistem ..................................................... 45 III.2.5 Pembangunan & Integrasi Sistem ................................................. 45 III.2.6 Validasi & Penyerahan.................................................................. 46 III.3 Metode Pengumpulan Data dan Pengolahan Data............................ 46 III.3.1 Metode Pengumpulan Data ........................................................... 46 III.3.2 Metode Pengolahan Data .............................................................. 48 III.4 Metode Evaluasi ............................................................................... 52 DAFTAR PUSTAKA ......................................................................................... 54 LAMPIRAN ........................................................................................................ 58
II

DAFTAR GAMBAR
Gambar I.1 Pertumbuhan GMV E-Commerce dan Status Digitalisasi Usaha di Indonesia ............................................................................................................... 2 Gambar II.1 Alur Metode Waterfall Menurut (Pressman, 2015). ...................... 25 Gambar III.1 Sistematika Penyelesaian Masalah Menggunakan Metode Waterfall (Pressman, 2015) ................................................................................................ 44 Gambar III.2 Proses Bisnis AS-IS PT Agro Pangan Maju ................................. 49 Gambar III.3 Proses Bisnis TO-BE Usulan PT Agro Pangan Maju .................. 51
III

DAFTAR TABEL
Tabel II.1 Simbol Use Case Diagram ................................................................. 17 Tabel II.2 Simbol Activity Diagram .................................................................... 20 Tabel II.3 Simbol Suquence Diagram ................................................................. 22 Tabel II.4 Simbol Class Diagram ....................................................................... 24 Tabel II.5 Penelitian Terdahulu .......................................................................... 30 Tabel II.6 Perbandingan Metode ........................................................................ 39
IV

DAFTAR LAMPIRAN
Lampiran 1 Foto Wawancara Dengan Narasumber .......................................... 58 Lampiran 2 Laporan Laba Rugi PT Agro Pangan Maju .................................... 60 Lampiran 3 Laporan Penjualan Menggunakan Excel ........................................ 61 Lampiran 4 Transkrip Wawancara Dengan Narasumber .................................. 64 Lampiran 5 Gambar Proses Bisnis AS ­ IS PT Agro Pangan Maju................... 65 Lampiran 6 Gambar Proses Bisnis To ­ Be PT Agro Pangan Maju .................. 66
V

DAFTAR ISTILAH

E-Commerce
Chatbot Rule-Based
Waterfall SDLC
BPMN
As-Is To-Be UAT
Black Box Testing API
Bottleneck

: Sistem berbasis web yang dirancang untuk mendukung aktivitas perdagangan elektronik antara penjual dan pembeli melalui jaringan internet, menggabungkan fungsi media informasi dan transaksi daring.
: Sistem percakapan otomatis yang dirancang untuk memberikan respon kepada pengguna berdasarkan pola atau aturan tertentu yang telah didefinisikan sebelumnya.
: Pendekatan pengembangan chatbot di mana respon sistem sepenuhnya ditentukan oleh daftar aturan atau kata kunci yang telah ditetapkan, tanpa menggunakan pembelajaran mesin yang kompleks
: Metode pengembangan perangkat lunak yang menekankan pendekatan sistematis dan berurutan, dimulai dari analisis kebutuhan, perancangan, implementasi, hingga pengujian.
: Software Development Life Cycle, kerangka kerja yang menggambarkan siklus hidup sistem informasi dari perencanaan awal hingga sistem tersebut tidak lagi digunakan.
: Business Process Model and Notation, standar visualisasi yang digunakan untuk memetakan alur kerja operasional (proses bisnis) guna mendeteksi inefisiensi atau merancang alur baru.
: Istilah yang merujuk pada kondisi atau mekanisme proses bisnis yang sedang berjalan saat ini sebelum adanya perbaikan atau implementasi sistem baru.
: Istilah yang merujuk pada rancangan proses bisnis usulan yang diharapkan terjadi di masa depan setelah solusi sistem diterapkan.
: User Acceptance Testing, fase pengujian akhir yang dilakukan langsung oleh pengguna di lingkungan operasional nyata untuk memvalidasi apakah sistem dapat diterima dan sesuai kebutuhan bisnis.
: Teknik pengujian perangkat lunak yang berfokus pada fungsionalitas input dan output tanpa memeriksa struktur kode internal program.
: Application Programming Interface, antarmuka yang memungkinkan sistem e-commerce terhubung dengan layanan pihak ketiga, seperti Midtrans untuk pembayaran dan RajaOngkir untuk logistik.
: Titik kemacetan dalam alur kerja operasional yang menghambat efisiensi, seperti keterlambatan informasi stok antar-divisi.

VII

BAB I PENDAHULUAN
Bab I ini merupakan bagian pendahuluan yang memberikan gambaran umum dan konteks mengenai keseluruhan penelitian. Pembahasan akan diawali dengan pemaparan latar belakang yang mengidentifikasi permasalahan utama di PT Agro Pangan Maju serta justifikasi kebutuhan sistem. Berdasarkan permasalahan tersebut, akan dijabarkan perumusan masalah secara spesifik. Selanjutnya, bab ini akan menguraikan tujuan penelitian yang ingin dicapai serta manfaat penelitian yang diharapkan, baik bagi perusahaan maupun bagi pengembangan keilmuan. Untuk memperjelas cakupan penelitian, akan ditetapkan pula batasan penelitian. Bab ini kemudian ditutup dengan penjelasan mengenai sistematika laporan yang akan memandu pembaca dalam memahami struktur penulisan tugas akhir secara menyeluruh.
I.1 Latar Belakang Pertumbuhan ekonomi digital di Indonesia menunjukkan peningkatan yang signifikan dalam beberapa tahun terakhir, seiring pesatnya perkembangan teknologi informasi dan meningkatnya penetrasi internet di berbagai wilayah. Laporan terbaru e-Conomy SEA 2024 mencatat bahwa nilai ekonomi digital Indonesia telah mencapai USD 90 miliar pada tahun 2024 atau tumbuh 13 persen dibandingkan tahun sebelumnya (Google, Temasek, & Bain Company, 2024). Dari total nilai tersebut, sektor e-commerce menjadi kontributor terbesar dengan nilai transaksi bruto (Gross Merchandise Value) mencapai USD 65 miliar, yang menegaskan posisinya sebagai penggerak utama ekonomi digital nasional (Google, Temasek, & Bain Company, 2024). Namun, pertumbuhan nilai pasar ini perlu disandingkan dengan tingkat kesiapan pelaku usaha di lapangan sebagaimana terekam dalam data statistik terbaru.
1

Sumber : (Badan Pusat Statistik, 2025; Google, Temasek, Bain & Company., 2024)
Gambar I.1 Pertumbuhan GMV E-Commerce dan Status Digitalisasi Usaha di Indonesia
Berdasarkan data pada Gambar I.1, terlihat fenomena yang kontras antara pertumbuhan pasar dengan adopsi teknologi. Meskipun nilai transaksi ecommerce terus menanjak, data Badan Pusat Statistik mengungkapkan bahwa sebesar 58,49% pelaku usaha di Indonesia masih menjalankan bisnisnya secara konvensional (non-e-commerce), sementara baru 41,51% yang telah terintegrasi ke dalam ekosistem digital (Badan Pusat Statistik, 2025). Fakta ini mengindikasikan bahwa perdagangan berbasis digital memang telah menjadi tulang punggung ekonomi modern, namun masih terdapat kesenjangan (gap) modernisasi yang cukup lebar. Kondisi ini pun relevan dengan sektor pangan, yang hingga kini masih menghadapi tantangan dalam penerapan digitalisasi, terutama pada aspek integrasi sistem stok dan kecepatan layanan pelanggan yang menuntut respons cepat serta akurat guna memenangkan persaingan di pasar yang semakin hibrida (Badan Pusat Statistik, 2025).
Dalam konteks tersebut, perusahaan distribusi dan penjualan produk pangan dituntut melakukan modernisasi sistem agar lebih efisien dan adaptif terhadap dinamika pasar. PT Agro Pangan Maju merupakan perusahaan distribusi dan penjualan produk pangan berskala nasional yang menjalankan model bisnis Business-to-Business (B2B), melayani pemesanan dalam volume besar untuk berbagai mitra usaha. Meskipun memiliki skala operasi yang masif, saat ini
2

perusahaan masih menjalankan proses bisnis dengan sistem operasional yang belum terdigitalisasi secara penuh dan belum terintegrasi ke dalam satu sistem informasi yang menyeluruh, sehingga sinkronisasi data lintas fungsi kerap terhambat dan menimbulkan inkonsistensi informasi antara permintaan pelanggan dan ketersediaan stok aktual (Santoso, 2025). Selain itu, mekanisme layanan pelanggan belum didukung pusat informasi terpadu, menyebabkan waktu tanggapan tidak konsisten (Santoso, 2025). Berdasarkan laporan Laba/Rugi PT Agro Pangan Maju 2024, perusahaan telah mencatat pendapatan tahunan di atas Rp 50 miliar, namun kinerja operasional belum optimal karena sistem manajemen penjualan dan pelayanan pelanggan belum sepenuhnya terdigitalisasi (Santoso, 2025). Kondisi ini menciptakan kesenjangan antara potensi pertumbuhan perusahaan dan kemampuan sistem yang ada dalam meningkatkan kepuasan pelanggan secara menyeluruh, terutama mengingat karakteristik pelanggan B2B yang menuntut kecepatan dan ketepatan informasi yang tinggi (Santoso, 2025).
Untuk menjawab permasalahan tersebut, penelitian ini mengusulkan pengembangan sistem berbasis website e-commerce yang terintegrasi dengan fitur chatbot berbasis rule-based. Implementasi sistem ini diharapkan memberikan dampak langsung berupa otomatisasi sinkronisasi data stok dan percepatan respons layanan kepada mitra bisnis (B2B). Hal ini selaras dengan pergeseran perilaku pasar bisnis yang kini menuntut kemudahan transaksi elektronik guna mengurangi hambatan prosedural dalam pembelian rutin (Kotler & Keller, 2016). Selain itu, kehadiran fitur chatbot memungkinkan perusahaan menyediakan akses informasi produk dan layanan transaksi tanpa batasan waktu operasional kantor, sehingga meningkatkan ketersediaan layanan bagi pelanggan (Purwaningtias dkk., 2020). Guna menjamin keberhasilan pembangunan sistem tersebut, metode pengembangan yang digunakan adalah Waterfall (Pressman, 2015). Metode Waterfall menekankan tahapan pengembangan yang terstruktur dan berurutan mulai dari analisis kebutuhan, perancangan, implementasi, pengujian, hingga pemeliharaan, sehingga sesuai untuk proyek dengan kebutuhan sistem yang telah terdefinisi jelas sejak awal (Pressman, 2015). Dalam konteks PT Agro Pangan Maju yang memiliki alur bisnis relatif stabil, pendekatan ini relevan karena menghasilkan dokumentasi komprehensif pada setiap tahap, memudahkan proses
3

evaluasi, serta memastikan stabilitas sistem pascaimplementasi (Nur Rais dkk., 2022). Dengan demikian, Waterfall dipandang tepat untuk meminimalkan risiko kesalahan, meningkatkan kualitas pengujian, dan memastikan kesesuaian hasil akhir dengan kebutuhan bisnis perusahaan (Nur Rais dkk., 2022).
Sebagai penerapan dari metode tersebut, sistem yang dikembangkan berupa website e-commerce dengan chatbot yang dirancang untuk menjangkau dua segmen pasar, yakni memfasilitasi transaksi skala besar untuk mitra bisnis (B2B) sekaligus membuka akses layanan langsung kepada konsumen akhir (B2C) dalam satu ekosistem terintegrasi. Fitur chatbot menggunakan pendekatan rule-based, yakni memberikan tanggapan berdasarkan kata kunci atau pola kalimat yang telah ditetapkan sehingga efektif untuk menangani pertanyaan berulang misalnya ketersediaan produk, harga, atau status pesanan tanpa memerlukan pemrosesan bahasa alami yang kompleks (Hidayat dkk., 2025). Pendekatan ini memungkinkan sistem menarik data langsung dari basis data internal untuk menyajikan jawaban yang cepat dan konsisten, sekaligus menurunkan beban kerja petugas layanan dalam menangani proses administrasi rutin (Hidayat dkk., 2025). Di sisi lain, karakteristik website dan kualitas layanan pengiriman terbukti berpengaruh signifikan terhadap kepuasan pelanggan pada sistem e-commerce, sehingga perancangan antarmuka yang mudah digunakan, aman, dan responsif menjadi krusial. Berdasarkan hal tersebut, objek penelitian ini dirancang untuk menghasilkan manfaat nyata berupa infrastruktur digital yang mampu mengeliminasi hambatan operasional konvensional dan menjamin transparansi ketersediaan stok secara real-time. Dengan demikian, sistem ini tidak hanya bermanfaat untuk memperkuat loyalitas pelanggan B2B melalui kemudahan transaksi, tetapi juga berpotensi memperluas jangkauan pasar B2C melalui standar layanan yang adaptif dan responsif terhadap kebutuhan konsumen digital (Hidayat dkk., 2025; Kotler & Keller, 2016).
4

I.2 Perumusan Masalah
Rumusan masalah yang mendasari penelitian ini adalah: a. Bagaimana cara mengembangkan website e-commerce dengan fitur chatbot pada PT Agro Pangan Maju guna mendukung proses digitalisasi penjualan, pelayanan pelanggan, dan peningkatan kinerja operasional perusahaan? b. Bagaimana penerapan metode Waterfall dalam pengembangan website ecommerce berfitur chatbot dapat memastikan sistem yang dibangun sesuai dengan kebutuhan bisnis dan mampu meningkatkan kepuasan pelanggan di PT Agro Pangan Maju?
I.3 Tujuan Penelitian
Penelitian ini bertujuan untuk: a. Mengembangkan website e-commerce dengan fitur chatbot pada PT Agro Pangan Maju sebagai sarana digitalisasi proses penjualan dan peningkatan kualitas pelayanan pelanggan, sehingga mampu mengoptimalkan produktivitas operasional dan kepuasan pelanggan perusahaan. b. Menerapkan metode Waterfall dalam pengembangan website e-commerce berfitur chatbot guna memastikan sistem yang dihasilkan sesuai dengan kebutuhan bisnis perusahaan serta mendukung proses transformasi digital PT Agro Pangan Maju secara terstruktur dan terukur.
I.4 Batasan Penelitian Batasan penelitian ini adalah sebagai berikut:
a. Penelitian ini difokuskan pada perancangan dan pengembangan website ecommerce dengan fitur chatbot untuk PT Agro Pangan Maju sebagai sarana digitalisasi proses penjualan dan peningkatan kepuasan pelanggan.
b. Proses pengembangan sistem dilakukan dengan menggunakan metode Waterfall yang mencakup tahapan analisis kebutuhan, perancangan, implementasi, pengujian, dan pemeliharaan guna memastikan hasil yang sistematis dan terdokumentasi dengan baik.
c. Sistem dibangun menggunakan bahasa pemrograman PHP dengan framework Laravel serta basis data MySQL, dan dilengkapi dengan fitur
5

utama seperti pengelolaan produk, pemesanan, pembayaran, pelacakan pengiriman, serta layanan pelanggan berbasis chatbot rule-based. d. Penelitian ini dibatasi pada perancangan dan pengujian fungsional sistem menggunakan metode black box testing. Analisis biaya, integrasi dengan sistem eksternal, serta evaluasi perilaku pengguna pascaimplementasi tidak termasuk dalam cakupan penelitian ini.
I.5 Manfaat Penelitian Manfaat penelitian ini:
a. Bagi PT Agro Pangan Maju, hasil penelitian ini diharapkan dapat memberikan solusi digital yang efektif dalam meningkatkan kinerja proses penjualan, mempercepat pelayanan pelanggan, serta memperkuat strategi bisnis perusahaan melalui penerapan website e-commerce berfitur chatbot. Sistem ini juga diharapkan mampu mengoptimalkan pengelolaan data transaksi secara terintegrasi dan meningkatkan kepuasan pelanggan terhadap layanan perusahaan.
b. Bagi peneliti dan pengembang sistem informasi lainnya, penelitian ini dapat menjadi referensi dalam penerapan metode Waterfall untuk pengembangan sistem e-commerce berbasis web yang terintegrasi dengan fitur chatbot, serta sebagai bahan pembelajaran tentang strategi digitalisasi bisnis di sektor distribusi pangan dalam mendukung peningkatan daya saing perusahaan di era transformasi digital.
I.6 Sitematika Penulisan Penulisan tugas akhir ini disusun dalam enam bab dengan sistematika penulisan sebagai berikut:
BAB I PENDAHULUAN
Bab ini berisi uraian mengenai latar belakang penelitian, perumusan masalah, tujuan penelitian, batasan penelitian, manfaat penelitian, serta sistematika laporan. Bagian ini menjelaskan alasan dilaksanakannya penelitian serta arah pengembangan sistem yang akan dibangun.
6

BAB II TINJAUAN PUSTAKA Bab ini membahas landasan teori yang menjadi dasar dalam penelitian, mencakup konsep e-commerce, sistem layanan pelanggan berbasis chatbot, metode pengembangan perangkat lunak Waterfall, serta penelitian terdahulu yang relevan dengan pengembangan sistem e-commerce di bidang distribusi pangan. BAB III METODE PENELITIAN Bab ini menjelaskan pendekatan dan metode yang digunakan dalam penelitian, meliputi kerangka berpikir, tahapan penyelesaian masalah, metode pengumpulan dan pengolahan data, serta alasan pemilihan metode Waterfall. Bab ini juga memaparkan rancangan jadwal kegiatan penelitian yang akan dilaksanakan. BAB IV PENYELESAIAN PERMASALAHAN Bab ini menguraikan proses pengembangan sistem website e-commerce berfitur chatbot, mulai dari tahap analisis kebutuhan, perancangan sistem, implementasi, hingga pengujian sistem menggunakan pendekatan Waterfall. Setiap tahapan dijelaskan secara rinci sesuai dengan alur pengembangan perangkat lunak yang telah ditetapkan. BAB V VALIDASI, ANALISIS HASIL, DAN IMPLIKASI Bab ini berisi hasil pengujian sistem menggunakan metode black box testing, analisis hasil pengujian, serta pembahasan mengenai dampak penerapan sistem terhadap peningkatan pelayanan pelanggan dan proses bisnis di PT Agro Pangan Maju. BAB VI KESIMPULAN DAN SARAN Bab ini berisi kesimpulan dari hasil penelitian serta saran untuk pengembangan sistem di masa mendatang, khususnya dalam peningkatan fitur dan optimalisasi kinerja sistem agar dapat lebih mendukung transformasi digital PT Agro Pangan Maju.
7

DAFTAR PUSTAKA Bagian ini berisi daftar seluruh referensi yang digunakan dalam penyusunan tugas akhir, meliputi buku, jurnal ilmiah, laporan resmi, dan sumber relevan lainnya yang menjadi dasar teori dan acuan penelitian.
8

BAB II LANDASAN TEORI
Bab ini menyajikan landasan teoretis dan tinjauan pustaka yang menjadi fondasi utama dalam pelaksanaan penelitian. Sistematika pembahasan dalam bab ini disusun menjadi dua bagian pokok. Bagian pertama adalah kajian teori, yang menjabarkan definisi serta konsep fundamental yang relevan dengan topik penelitian, mencakup sistem informasi, website, e-commerce, chatbot, Unified Modeling Language (UML), serta metode pengembangan Waterfall. Bagian kedua adalah penelitian terdahulu, yang memaparkan analisis kritis terhadap studi-studi sejenis yang telah dilakukan sebelumnya. Tinjauan ini bertujuan untuk memetakan posisi penelitian (state of the art) serta mengidentifikasi celah penelitian (research gap) yang akan diisi oleh pengembangan sistem dalam penelitian ini.
II.1 Materi Pendukung Riset
Bagian ini menguraikan kerangka konseptual dan definisi operasional yang digunakan sebagai acuan dalam proses perancangan serta pengembangan sistem. Pembahasan diawali dengan pemaparan konsep dasar sistem informasi, yang kemudian dispesifikasikan pada platform website dan e-commerce. Selanjutnya, diuraikan tinjauan teoretis mengenai teknologi chatbot sebagai fitur utama yang diusulkan, serta landasan metodologis yang meliputi penggunaan Unified Modeling Language (UML) untuk pemodelan sistem dan metode Waterfall sebagai alur pengembangan perangkat lunak.
II.1.1 Sistem Informasi Sistem informasi merupakan kombinasi dari manusia, perangkat keras, perangkat lunak, jaringan komunikasi, dan data yang dirancang untuk mengumpulkan, memproses, menyimpan, serta mendistribusikan informasi guna mendukung pengambilan keputusan di dalam organisasi (O'Brien & Marakas, 2010). Melalui sistem informasi, suatu perusahaan dapat meningkatkan koordinasi dan efisiensi operasional karena setiap komponen bekerja secara terintegrasi untuk mencapai tujuan bisnis (O'Brien & Marakas,
9

2010). Dengan demikian, sistem informasi tidak hanya berfungsi sebagai alat bantu teknis, tetapi juga sebagai komponen strategis dalam mendukung proses bisnis.
Menurut Hadion (Wijoyo dkk., 2021), sistem informasi terdiri dari beberapa jenis berdasarkan fungsinya, seperti Transaction Processing System (TPS) untuk kegiatan operasional, Management Information System (MIS) untuk membantu manajemen dalam pengambilan keputusan, dan Decision Support System (DSS) yang memberikan analisis berbasis data untuk keputusan strategis. Interaksi antarjenis sistem ini membentuk satu kesatuan yang mendukung efektivitas aliran informasi di seluruh level organisasi (Wijoyo dkk., 2021). Dengan integrasi sistem tersebut, perusahaan dapat mengurangi redundansi data dan mempercepat proses penyajian informasi.
Dalam perkembangan era digital, sistem informasi mengalami transformasi signifikan melalui pemanfaatan teknologi web dan jaringan internet (O'Brien & Marakas, 2010). Integrasi sistem informasi dengan platform digital memungkinkan organisasi memperluas jangkauan layanan hingga ke pelanggan, mitra bisnis, maupun pemasok. Hal ini menjadikan sistem informasi tidak lagi terbatas pada kegiatan internal perusahaan, tetapi juga mencakup interaksi eksternal dalam bentuk sistem daring seperti website dan e-commerce (Ghezzi dkk., 2003). Oleh karena itu, pengembangan sistem informasi di perusahaan seperti PT Agro Pangan Maju perlu diarahkan pada digitalisasi proses bisnis untuk mencapai efisiensi yang lebih tinggi dan kepuasan pelanggan yang maksimal.
II.1.2 Website Website merupakan kumpulan halaman digital yang saling terhubung dan dapat diakses melalui jaringan internet untuk menyampaikan informasi, layanan, atau interaksi kepada pengguna (Ghezzi dkk., 2003). Dalam konteks sistem informasi, website menjadi sarana utama yang menghubungkan pengguna dengan sistem secara langsung, sehingga berperan penting dalam proses penyajian data dan layanan berbasis web (Leach, 2016). Website tidak hanya berfungsi sebagai media informasi, tetapi juga sebagai antarmuka
10

interaktif yang memungkinkan pengguna melakukan berbagai aktivitas, mulai dari pencarian data hingga transaksi daring.
Secara umum, website terbagi menjadi dua bagian utama, yaitu front-end dan back-end. Front-end berfokus pada tampilan dan interaksi dengan pengguna melalui desain antarmuka, sedangkan back-end berfungsi mengelola logika sistem, basis data, dan komunikasi antara pengguna dengan server (Booch dkk., 2005). Keseimbangan antara kedua aspek ini sangat penting untuk menciptakan pengalaman pengguna yang optimal. Website yang dirancang dengan baik harus mempertimbangkan aspek estetika, fungsionalitas, serta efisiensi proses agar dapat berfungsi secara efektif (Dennis dkk., 2012).
Keberhasilan sebuah website sangat bergantung pada tiga faktor utama, yaitu kegunaan (usability), keamanan (security), dan kinerja (performance) (Dennis dkk., 2015). Website dengan tingkat kegunaan tinggi akan mempermudah pengguna dalam menavigasi fitur dan memahami informasi yang tersedia, sedangkan keamanan menjamin perlindungan data pelanggan dari ancaman eksternal. Selain itu, performa yang stabil dan cepat akan meningkatkan kenyamanan pengguna dalam berinteraksi dengan sistem.
Dalam konteks bisnis, website berperan strategis sebagai media promosi dan interaksi pelanggan (O'Brien & Marakas, 2010). Bagi perusahaan seperti PT Agro Pangan Maju, website dapat dijadikan platform utama dalam menampilkan produk, menerima pesanan, dan memberikan layanan pelanggan secara daring. Integrasi website dengan sistem informasi internal memungkinkan proses bisnis berjalan secara otomatis dan efisien. Dengan demikian, website tidak hanya menjadi sarana komunikasi, tetapi juga pondasi utama dalam implementasi e-commerce yang modern dan berorientasi pada kepuasan pelanggan.
II.1.3 Website E-Commerce Website e-commerce merupakan sistem berbasis web yang dirancang untuk mendukung aktivitas perdagangan elektronik antara penjual dan pembeli melalui jaringan internet (O'Brien & Marakas, 2010). Sistem ini
11

menggabungkan fungsi utama dari website sebagai media informasi dengan fitur e-commerce yang berfokus pada transaksi daring (Dennis dkk., 2012). Dengan demikian, website e-commerce tidak hanya berperan sebagai sarana promosi, tetapi juga sebagai platform operasional yang mengintegrasikan manajemen produk, pemesanan, pembayaran, serta layanan pelanggan secara otomatis.
Menurut (Laudon & Traver, 2021) terdapat beberapa elemen penting yang harus diperhatikan dalam pembangunan website e-commerce, yaitu content management, user interface design, transaction processing, dan security management. Content management berfungsi untuk mengatur dan memperbarui informasi produk, sedangkan user interface design berperan dalam menciptakan pengalaman pengguna yang mudah dan intuitif. Transaction processing mencakup proses pemesanan, pembayaran, hingga konfirmasi transaksi, sementara security management memastikan keamanan data pengguna serta perlindungan terhadap ancaman siber. Keempat komponen ini harus bekerja secara terpadu untuk menghasilkan sistem ecommerce yang andal dan responsif terhadap kebutuhan pengguna.
Seperti yang dijelaskan oleh (Ghezzi dkk., 2003) bahwa website e-commerce yang efektif mampu memberikan nilai tambah bagi perusahaan melalui peningkatan proses bisnis, transparansi transaksi, serta perluasan jangkauan pasar. Dalam konteks PT Agro Pangan Maju, sistem ini dapat digunakan untuk mempercepat proses pemesanan dan penjualan produk kepada pelanggan secara daring, sekaligus menyediakan informasi real-time terkait ketersediaan stok barang dan status pengiriman. Selain itu, integrasi fitur chatbot pada website e-commerce dapat meningkatkan pengalaman pelanggan dengan memberikan respon otomatis terhadap pertanyaan dan kebutuhan informasi mereka.
Berdasarkan penelitian (Cruzado dkk., 2023), website e-commerce yang dirancang dengan antarmuka interaktif dan responsif memiliki pengaruh signifikan terhadap peningkatan loyalitas pelanggan. Hal ini disebabkan oleh kemudahan pengguna dalam menavigasi sistem, memperoleh informasi
12

produk, dan melakukan transaksi tanpa hambatan. Oleh karena itu, pengembangan website e-commerce pada PT Agro Pangan Maju perlu mempertimbangkan aspek desain yang ramah pengguna, kecepatan akses, serta keamanan data agar dapat menciptakan sistem yang efisien dan mendukung kepuasan pelanggan.
Dalam implementasinya, pembangunan website e-commerce umumnya menggunakan pendekatan software engineering dengan metode Waterfall untuk memastikan seluruh tahapan pengembangan dilakukan secara terstruktur dan terdokumentasi dengan baik (Pressman, 2015). Dengan pendekatan ini, setiap tahap mulai dari analisis kebutuhan, perancangan sistem, hingga pengujian dapat dilakukan secara sistematis sehingga menghasilkan sistem yang stabil dan sesuai dengan tujuan bisnis perusahaan.
II.1.4 Business to Business (B2B) Business to Business (B2B) didefinisikan sebagai model transaksi yang memanfaatkan media digital untuk menghubungkan proses bisnis antar perusahaan atau organisasi, di mana fokus utamanya adalah pertukaran informasi dan integrasi rantai pasok (Purwaningtias dkk., 2020). Berbeda dengan pasar konsumen, pasar bisnis memiliki karakteristik struktur pasar yang unik, yakni terdiri dari jumlah pembeli yang lebih sedikit namun memiliki volume pembelian yang jauh lebih besar dan bernilai tinggi (Kotler & Keller, 2016). Dalam ekosistem B2B, hubungan antara pemasok dan pelanggan cenderung lebih erat dan bersifat jangka panjang karena pembeli bisnis sangat bergantung pada keandalan pasokan untuk menjaga keberlangsungan operasional produksi mereka (Kotler & Keller, 2016).
Penerapan teknologi dalam model B2B bertujuan untuk menciptakan kolaborasi bisnis yang lebih optimal melalui integrasi data elektronik. Sistem B2B memungkinkan mitra bisnis melakukan pertukaran dokumen krusial seperti faktur, data inventaris, dan status pengiriman secara real-time, yang berdampak pada pengurangan biaya administrasi serta percepatan siklus pemesanan (Purwaningtias dkk., 2020). Selain itu, proses pembelian di pasar bisnis seringkali melibatkan prosedur yang lebih formal, seperti negosiasi
13

harga dan verifikasi spesifikasi teknis yang ketat, sehingga sistem informasi yang dibangun harus mampu mengakomodasi logika bisnis yang terstruktur tersebut (Kotler & Keller, 2016).
Dalam konteks operasional PT Agro Pangan Maju, penerapan konsep B2B menawarkan keunggulan strategis berupa stabilitas pendapatan melalui volume transaksi besar dan loyalitas mitra jangka panjang. Namun, model ini juga menghadirkan tantangan tersendiri, yaitu kompleksitas prosedur administrasi dan tuntutan mitra bisnis akan akurasi data stok yang tinggi. Ketidaksesuaian data sedikit saja dapat berisiko merusak kepercayaan mitra yang bergantung pada pasokan rutin (Purwaningtias dkk., 2020). Oleh karena itu, sistem yang dikembangkan dalam penelitian ini dirancang secara spesifik untuk memitigasi risiko tersebut dengan memprioritaskan fitur sinkronisasi stok otomatis dan validasi pesanan berjenjang guna memenuhi standar ketat kebutuhan mitra bisnis.
II.1.5 Business to Customer (B2C) Business to Consumer (B2C) merupakan mekanisme perdagangan elektronik di mana transaksi penjualan produk atau jasa terjadi secara langsung antara perusahaan dengan konsumen akhir untuk penggunaan pribadi (Purwaningtias dkk., 2020). Model bisnis ini memiliki karakteristik jangkauan pasar yang luas dan global, di mana konsumen dapat mengakses katalog produk serta melakukan transaksi kapan saja selama 24 jam tanpa dibatasi oleh waktu operasional kantor fisik (Purwaningtias dkk., 2020). Keunggulan utama dari model B2C terletak pada penyajian antarmuka yang transparan dan kemampuan self-service, yang memudahkan konsumen dalam membandingkan harga dan menyelesaikan pembayaran secara mandiri (Purwaningtias dkk., 2020).
Meskipun secara tradisional berbeda dengan B2B, tren perilaku pasar saat ini menunjukkan adanya fenomena di mana pembeli bisnis mulai mengadopsi pola pikir konsumen ritel. Pelaku bisnis kini semakin menuntut kenyamanan dan kemudahan transaksi elektronik guna mengurangi hambatan prosedural
14

dalam pembelian rutin, menyerupai pengalaman instan yang biasa mereka dapatkan di pasar konsumen (Kotler & Keller, 2016). Pergeseran ini mendorong perusahaan untuk mengadopsi pendekatan yang berorientasi pada pengguna (user-oriented) dalam sistem pengadaan mereka, guna meningkatkan kepuasan dan loyalitas pelanggan melalui layanan yang lebih responsif (Kotler & Keller, 2016).
Dalam implementasinya di PT Agro Pangan Maju, adopsi karakteristik B2C memberikan nilai tambah berupa pengalaman pengguna yang intuitif melalui akses katalog visual dan fleksibilitas waktu transaksi. Namun, pendekatan yang lebih terbuka ke arah konsumen ritel ini membawa tantangan tersendiri, yaitu lonjakan volume interaksi pelanggan yang menuntut respons cepat terkait informasi produk dan harga. Tingginya ekspektasi terhadap layanan instan ini berpotensi membebani admin jika dikelola secara konvensional (Kotler & Keller, 2016). Oleh karena itu, penelitian ini mengintegrasikan fitur otomatisasi layanan melalui Chatbot, yang berfungsi untuk menyeimbangkan kemudahan ala B2C dengan efisiensi operasional, memastikan setiap pertanyaan pelanggan tertangani tanpa menambah beban kerja administrasi secara signifikan.
II.1.6 Chatbot Chatbot merupakan sistem percakapan otomatis yang dirancang untuk memberikan respon berdasarkan pola atau aturan tertentu yang telah didefinisikan sebelumnya. Sistem ini bekerja dengan mencocokkan masukan pengguna terhadap kumpulan kata kunci atau struktur kalimat yang telah disiapkan sehingga chatbot mampu memberikan jawaban yang cepat dan konsisten terhadap pertanyaan yang bersifat berulang (Arsyadin, 2025). Dalam konteks layanan digital, chatbot berfungsi sebagai media interaksi yang memungkinkan pengguna memperoleh informasi secara instan tanpa perlu menunggu respon dari petugas layanan, sehingga dapat meningkatkan efisiensi operasional perusahaan (Sahata Sitanggang dkk., 2023).
15

Dalam ekosistem e-commerce, chatbot berperan penting sebagai alat pendukung pelayanan pelanggan yang mampu memberikan informasi mengenai produk, proses pembelian, hingga status pesanan secara otomatis. Penelitian menunjukkan bahwa penerapan chatbot dapat mempercepat proses pelayanan dan meminimalkan keterlambatan respon karena sistem mampu melayani pertanyaan pelanggan kapan pun secara otomatis (Hidayat dkk., 2025). Kemampuan ini memberikan nilai tambah bagi perusahaan yang memiliki volume interaksi pelanggan tinggi, karena chatbot dapat menangani pertanyaan berulang tanpa mengurangi kualitas layanan.
Selain itu, penggunaan chatbot dalam platform e-commerce terbukti meningkatkan kualitas pengalaman pengguna. Pada penelitian lain disebutkan bahwa chatbot memberikan kemudahan dalam memperoleh informasi, membantu navigasi pembelian, serta meningkatkan kepuasan pelanggan melalui kecepatan respon yang tinggi (Sahata Sitanggang dkk., 2023). Chatbot juga dapat mengurangi beban kerja admin karena banyak tugas yang bersifat rutin dan repetitif dapat dialihkan kepada sistem otomatis (Hidayat dkk., 2025).
Secara teknis, terdapat dua pendekatan utama dalam pengembangan chatbot, yaitu chatbot berbasis aturan (rule-based) dan chatbot berbasis kecerdasan buatan (AI-based). Pada sistem rule-based, chatbot memberikan respon yang sepenuhnya ditentukan oleh daftar aturan atau kata kunci yang telah didefinisikan sebelumnya. Pendekatan ini cocok untuk menjawab pertanyaan sederhana dan terstruktur, seperti pengecekan stok produk atau informasi harga (Arsyadin, 2025). Sementara itu, chatbot berbasis AI menggunakan teknik pemrosesan bahasa alami dan pembelajaran mesin untuk menghasilkan respon yang lebih fleksibel dan kontekstual.
Pada perusahaan seperti PT Agro Pangan Maju, penggunaan chatbot berbasis rule-based dinilai lebih sesuai karena kebutuhan utama sistem adalah memberikan informasi cepat terkait stok, harga, pemesanan, dan status pengiriman yang sifatnya terstruktur. Dengan demikian, chatbot dapat mendukung peningkatan kualitas layanan melalui penyediaan jawaban
16

otomatis secara konsisten dan mengurangi ketergantungan pada admin dalam menangani pertanyaan umum pelanggan (Hidayat dkk., 2025).
II.1.7 UML Unified Modeling Language (UML) merupakan bahasa pemodelan standar yang digunakan untuk memvisualisasikan, merancang, dan mendokumentasikan sistem perangkat lunak secara terstruktur. UML menyediakan berbagai jenis diagram yang merepresentasikan aspek fungsional, struktural, dan interaksi dalam sistem, sehingga memudahkan analis dan pengembang dalam memahami kebutuhan pengguna dan merancang solusi perangkat lunak secara lebih sistematis (Booch dkk., 2005) Menurut (Pressman, 2015) UML membantu mengurangi kompleksitas dalam pengembangan perangkat lunak karena menyediakan notasi baku untuk menggambarkan berbagai komponen sistem. (Dennis dkk., 2015) juga menegaskan bahwa UML meningkatkan konsistensi komunikasi antara pemangku kepentingan karena diagram dapat menjelaskan struktur maupun perilaku sistem secara jelas sebelum tahap implementasi dilakukan.
A. Use Case Diagram Use Case Diagram digunakan untuk menggambarkan hubungan antara aktor dengan fungsi-fungsi yang disediakan oleh sistem. Diagram ini memberikan pandangan menyeluruh mengenai interaksi yang terjadi antara pengguna luar dengan sistem untuk mencapai tujuan tertentu (Booch dkk., 2005). Menurut (Dennis dkk., 2015), use case berfungsi sebagai dasar dalam melakukan analisis kebutuhan karena mendokumentasikan apa saja yang seharusnya dapat dilakukan sistem dari perspektif pengguna.
Tabel II.1 Simbol Use Case Diagram
17

Simbol

Deskripsi

Aktor merupakan elemen yang

digunakan

untuk

menggambarkan peran dari

seseorang, pengguna, atau entitas

eksternal lain seperti sistem

maupun perangkat keras yang

berinteraksi langsung dengan

sistem

yang

sedang

dikembangkan. Simbol ini

menunjukkan siapa atau apa

yang berinisiatif melakukan

suatu tindakan atau berpartisipasi

dalam fungsi-fungsi yang

disediakan oleh sistem.

Use Case merupakan simbol

yang digunakan untuk

menggambarkan

rangkaian

tindakan atau kegiatan tertentu

yang dijalankan oleh sistem

untuk menghasilkan output yang

bernilai bagi aktor. Simbol ini

merepresentasikan suatu fungsi

inti atau proses bisnis yang dapat

dilakukan sistem sebagai

jawaban atas permintaan yang

diberikan oleh aktor.

Asosiasi (Association) adalah

simbol berupa garis panah atau

garis penghubung yang

menunjukkan hubungan antara

aktor dan use case yang

18

Simbol

Deskripsi digunakan. Garis ini berfungsi sebagai representasi visual dari interaksi atau komunikasi yang terjadi antara pengguna dan aktivitas yang dijalankan oleh sistem. Relasi Include merupakan simbol yang menunjukkan bahwa suatu use case wajib melibatkan atau memanggil fungsionalitas dari use case lainnya. Hal ini menandakan bahwa aktivitas tersebut harus selalu dijalankan sebagai bagian yang tidak dapat dipisahkan dari alur utama use case. Relasi Extend adalah simbol yang menunjukkan adanya fitur atau aktivitas tambahan yang dapat memperluas alur dari use case utama. Aktivitas ini bersifat opsional dan hanya dijalankan ketika kondisi tertentu terpenuhi, sehingga tidak wajib terjadi dalam setiap eksekusi use case utama. Generalisasi adalah hubungan pewarisan antara dua elemen, di mana elemen yang lebih khusus (child) mewarisi sifat, perilaku, dan struktur dari elemen yang

19

Simbol

Deskripsi lebih umum (parent). Simbol ini menunjukkan bahwa elemen child adalah jenis yang lebih spesifik dari elemen parent dan dapat menggantikan peran elemen parent.

B. Activity Diagram

Activity Diagram digunakan untuk memodelkan alur aktivitas di dalam sistem dan menunjukkan urutan proses bisnis secara terstruktur. Diagram ini membantu mengidentifikasi langkah-langkah proses, keputusan yang mungkin terjadi, serta hubungan antar aktivitas (Dennis dkk., 2015). (Booch dkk., 2005) menjelaskan bahwa diagram aktivitas sangat berguna pada tahap analisis karena dapat menggambarkan logika proses secara visual sehingga mempermudah pemahaman terhadap alur kerja sistem.

Tabel II.2 Simbol Activity Diagram

Simbol

Deskripsi

Simbol ini digunakan untuk

menunjukkan titik mulai atau

tahap awal dari keseluruhan

rangkaian proses dalam diagram

aktivitas.

Simbol ini menunjukkan aktivitas

atau tindakan spesifik yang

sedang dikerjakan oleh pengguna

maupun sistem dalam alur proses.

Simbol ini menandakan adanya

titik pengambilan keputusan

dalam alur, di mana proses dapat

berlanjut ke cabang berbeda

20

Simbol

Deskripsi

sesuai dengan kondisi yang

terpenuhi, umumnya berupa

pilihan "ya" atau "tidak."

Line Connector adalah simbol

yang berfungsi sebagai

penghubung antar aktivitas dalam

suatu alur proses, sehingga setiap

langkah dalam sistem dapat

terjalin secara berurutan dan jelas.

Simbol ini digunakan untuk

menunjukkan titik selesai atau

berakhirnya seluruh rangkaian

proses yang ditampilkan dalam

diagram aktivitas.

Ini merupakan mekanisme visual

berupa garis pembatas, baik

horizontal maupun vertikal, yang

digunakan

untuk

mengelompokkan

serta

memisahkan aktivitas sesuai

dengan aktor--baik pengguna

maupun

sistem--yang

bertanggung

jawab

menjalankannya.

C. Sequence Diagram
Sequence Diagram merupakan diagram yang menggambarkan interaksi antar objek berdasarkan urutan waktu. Diagram ini memperlihatkan bagaimana pesan dikirim antar objek untuk menyelesaikan suatu skenario dalam sistem (Booch dkk., 2005). Menurut (Pressman, 2015), sequence

21

diagram sangat penting untuk memahami dinamika sistem dan menjadi acuan dalam merancang interaksi logis antar komponen perangkat lunak.

Tabel II.3 Simbol Suquence Diagram

Simbol

Deskripsi

Simbol Actor (Aktor) merupakan

representasi yang digunakan

untuk menunjukkan peran

seorang pengguna atau individu

yang berinteraksi secara langsung

dengan sistem.

Simbol Activation Box (Kotak

Aktivasi) merupakan representasi

visual yang menunjukkan durasi

waktu ketika suatu objek berada

dalam keadaan aktif, seperti saat

menjalankan sebuah operasi atau

memproses suatu tugas tertentu.

Simbol Objek (Objek) merupakan

representasi dari suatu entitas atau

instansi tertentu dalam sistem

yang terlibat dalam interaksi,

seperti melakukan pertukaran

pesan dengan objek lain, selama

rangkaian proses berlangsung.

Simbol Lifeline (Garis Hidup)

adalah garis vertikal--umumnya

digambarkan putus-putus--yang

terhubung dengan sebuah objek

dan menunjukkan keberadaan

atau durasi hidup objek tersebut

22

Simbol

Deskripsi selama proses interaksi dalam diagram berlangsung. Simbol Message Call (Panggilan Pesan) digunakan untuk menggambarkan bahwa sebuah objek mengirimkan permintaan agar suatu operasi atau metode dijalankan. Permintaan tersebut dapat diarahkan kepada objek lain maupun kembali kepada dirinya sendiri dalam bentuk panggilan internal. Simbol Message Return (Pesan Kembali) merupakan representasi dari balasan atau output yang dikirim sebuah objek setelah menyelesaikan eksekusi operasi atau metode yang diminta sebelumnya. Simbol ini menunjukkan bahwa proses telah selesai dijalankan dan hasilnya dikembalikan kepada objek yang mengirimkan permintaan awal.

D. Class Diagram
Class Diagram digunakan untuk menggambarkan struktur statis dari sistem, mencakup kelas, atribut, operasi, serta hubungan antar kelas seperti asosiasi, agregasi, dan pewarisan (Pressman, 2015). (Dennis dkk., 2015) menyatakan bahwa class diagram merupakan inti dari pemodelan

23

berorientasi objek karena menentukan struktur data dan hubungan antar entitas sistem yang akan digunakan pada tahap implementasi.

Tabel II.4 Simbol Class Diagram

Simbol

Deskripsi

Simbol ini, yang biasanya

ditampilkan sebagai sebuah

garis lurus, menggambarkan

hubungan generalisasi atau

pewarisan. Relasi tersebut

menunjukkan bahwa objek

turunan (descendant) akan

mewarisi seluruh atribut dan

metode yang telah ditetapkan

pada objek induk (ancestor).

Simbol tabel ini digunakan

untuk

menggambarkan

sekumpulan objek yang

memiliki kesamaan dalam

atribut maupun operasi yang

dimilikinya.

II.1.8 Metode Waterfall Metode Waterfall merupakan salah satu model dalam Software Development Life Cycle (SDLC) yang menekankan pendekatan pengembangan sistem secara berurutan dan terstruktur dari tahap awal hingga akhir (Pressman, 2015). Setiap fase pada model ini harus diselesaikan terlebih dahulu sebelum melanjutkan ke tahap berikutnya, sehingga alur pengembangan bersifat linier. Pendekatan ini memudahkan proses dokumentasi dan pengendalian proyek karena setiap tahap memiliki keluaran (deliverable) yang jelas dan terdokumentasi (Kendall & Kendall, 2011).

24

Model Waterfall terbentuk dari konsep dasar SDLC yang mencakup serangkaian aktivitas sistematis dalam pembangunan perangkat lunak, seperti analisis, perancangan, implementasi, pengujian, dan pemeliharaan (Dennis dkk., 2012). SDLC sendiri merupakan kerangka kerja yang menggambarkan siklus hidup sistem informasi dari perencanaan awal hingga sistem tersebut tidak lagi digunakan (Leach, 2016). Dengan demikian, Waterfall dapat dipandang sebagai salah satu pendekatan klasik dari SDLC yang menekankan disiplin dan keteraturan dalam setiap tahapannya.
Gambar II.1 Alur Metode Waterfall Menurut (Pressman, 2015). Menurut (Pressman, 2015), model Waterfall (atau classic life cycle) menyarankan pendekatan sistematis dan berurutan untuk pengembangan perangkat lunak yang dimulai dengan spesifikasi kebutuhan pengguna dan berlanjut melalui tahapan berikut: 1. Komunikasi (Communication)
Tahap ini berfokus pada inisiasi proyek (project initiation) dan pengumpulan kebutuhan (requirements gathering) melalui kolaborasi dengan pelanggan atau pemangku kepentingan untuk memahami tujuan perangkat lunak dan mendefinisikan kebutuhan sistem. 2. Perencanaan (Planning) Tahap untuk membuat estimasi (estimating), penjadwalan (scheduling), dan pelacakan (tracking) proyek. Pada tahap ini, tim mendefinisikan rencana kerja rekayasa perangkat lunak, risiko yang mungkin terjadi, sumber daya yang dibutuhkan, produk kerja yang akan dihasilkan, dan jadwal kerja. 3. Pemodelan (Modeling) Tahap yang mencakup analisis (analysis) dan perancangan (design). Tujuannya adalah untuk lebih memahami kebutuhan sistem dan
25

menciptakan model atau representasi desain yang akan menjadi panduan bagi tahap konstruksi. 4. Konstruksi (Construction) Tahap ini menggabungkan pembuatan kode (code) dan pengujian (test). Code generation dilakukan untuk menerjemahkan model desain menjadi bahasa pemrograman, diikuti dengan pengujian untuk mengungkap kesalahan dalam kode tersebut. 5. Penyerahan (Deployment) Tahap di mana perangkat lunak dikirimkan (delivery) kepada pelanggan untuk dievaluasi. Tahap ini juga mencakup pemberian dukungan (support) dan pengumpulan umpan balik (feedback) dari pengguna untuk perbaikan sistem. Pendekatan Waterfall dinilai efektif untuk proyek dengan kebutuhan yang jelas dan stabil sejak awal (Mishra, 2012). Karena prosesnya terstruktur, model ini memungkinkan tim pengembang untuk melakukan pengawasan yang lebih ketat terhadap jadwal, anggaran, serta hasil kerja di setiap fase. Selain itu, dokumentasi yang lengkap pada setiap tahap mempermudah pelacakan kesalahan dan penyesuaian di masa mendatang (Pressman, 2015). Dalam konteks penelitian ini, metode Waterfall dipilih karena sesuai untuk pengembangan website e-commerce yang memiliki kebutuhan sistematis dan terukur. Setiap tahap mulai dari analisis kebutuhan hingga pengujian dapat dilakukan secara berurutan sehingga meminimalkan risiko kesalahan dan memastikan sistem berfungsi sesuai dengan kebutuhan PT Agro Pangan Maju. Dengan menerapkan metode Waterfall, proses pengembangan sistem diharapkan berjalan lebih terencana, terdokumentasi, dan menghasilkan produk akhir yang stabil serta dapat meningkatkan kepuasan pelanggan melalui kinerja sistem yang optimal.
26

II.1.9 User Acceptance Testing (UAT) User Acceptance Testing (UAT) merupakan fase krusial yang menandai tahap akhir dari rangkaian validasi perangkat lunak, di mana aktivitas pengujian dialihkan sepenuhnya ke lingkungan operasional yang sesungguhnya. Berbeda halnya dengan pengujian sistem yang biasanya terlaksana di bawah kendali tim pengembang, UAT dilaksanakan secara langsung di infrastruktur milik klien tempat aplikasi akan bekerja. Menurut pandangan (Spillner dkk., 2014) perpindahan konteks ini sangat esensial karena orientasi utamanya bukan lagi sekadar mendeteksi cacat teknis, melainkan untuk membangun keyakinan pemangku kepentingan bahwa aplikasi tersebut telah bebas dari kegagalan sistemik dan benar-benar sanggup mengakomodasi kebutuhan bisnis yang telah disepakati.
Dalam referensi yang sama, (Spillner dkk., 2014)menggarisbawahi urgensi untuk membedakan peran antara "Pelanggan" selaku pihak pengadaan sistem dan "Pengguna" yang akan mengoperasikannya setiap hari. UAT hadir sebagai mekanisme vital untuk menjembatani dan memverifikasi harapan dari kedua entitas tersebut. Kerap kali terjadi kondisi di mana spesifikasi kontrak secara administratif sudah terpenuhi, namun nuansa operasional di lapangan justru terabaikan. Oleh sebab itu, pelibatan langsung pengguna akhir dalam tahapan ini adalah kewajiban mutlak guna memastikan sistem dapat diterima dengan baik di seluruh lapisan organisasi.
Sorotan utama dalam fase ini tertuju pada kenyamanan serta pengalaman pengguna, mengingat penolakan terhadap teknologi baru sering kali dipicu oleh persepsi bahwa sistem tersebut kaku atau rumit (awkward to use), bukan semata-mata karena kerusakan fungsi. UAT didesain secara spesifik untuk mereduksi risiko penolakan ini dengan cara memvalidasi aspek kegunaan (usability) dari kacamata mereka yang paling intens berinteraksi dengan aplikasi. Alhasil, penilaian terhadap sebuah sistem tidak melulu soal kebenaran logika pemrogramannya, tetapi juga mencakup seberapa alami dan intuitif alur kerjanya bagi operator manusia.
27

Untuk menjamin bahwa hasil pengujian ini merefleksikan kondisi lapangan yang autentik, skenario yang diterapkan tidak boleh terbatas pada simulasi di atas kertas, melainkan harus menduplikasi proses bisnis riil (real business processes) perusahaan. (Spillner dkk., 2014)menganjurkan agar kasus uji disusun berdasarkan skenario penggunaan tipikal yang dijalankan staf dalam keseharian mereka. Melalui metode ini, pengujian mampu memberikan bukti empiris apakah sistem sanggup menghadapi dinamika kerja yang sebenarnya, sehingga keputusan penerimaan atau revisi sistem didasarkan pada fakta operasional yang valid.
II.1.10 Blackbox Testing Black Box Testing adalah teknik pengujian perangkat lunak yang berfokus pada spesifikasi fungsional dari perangkat lunak tersebut tanpa memperhatikan struktur kontrol internal atau kode programnya. (Wicaksono, 2022), metode ini memusatkan perhatian pada informasi domain perangkat lunak, yaitu input yang diberikan dan output yang dihasilkan, sehingga memungkinkan penguji untuk mengevaluasi sistem dari sudut pandang pengguna akhir. Dengan demikian, Black Box Testing menjadi pendekatan yang efektif untuk memastikan bahwa fungsi-fungsi perangkat lunak berjalan sesuai dengan kebutuhan pengguna tanpa perlu memahami kompleksitas teknis di baliknya.
Dalam penerapannya, tujuan utama Black Box Testing adalah menemukan keadaan di mana program tidak berjalan sesuai dengan spesifikasi yang telah ditentukan (Wicaksono, 2022). Menurut (Myers dkk., 2004) menyatakan bahwa strategi pengujian ini penting karena berkonsentrasi menemukan kesalahan fungsional tanpa mempedulikan perilaku internal atau struktur programnya. Hal ini sejalan dengan pemisahan konsep debugging dan testing yang diperkenalkan oleh Myers pada tahun 1979, di mana testing ditekankan sebagai proses untuk mencari kesalahan (finding errors), bukan sekadar memperbaikinya.
28

Salah satu teknik spesifik dalam Black Box Testing yang sering digunakan adalah Equivalence Partitioning. Teknik ini dirancang untuk meningkatkan efisiensi pengujian dengan cara membagi domain input program ke dalam kelas-kelas data atau partisi. Pengujian kemudian dilakukan berdasarkan penentuan nilai input yang mewakili setiap kelas tersebut. Menurut (Wicaksono, 2022), tujuan utama dari Equivalence Partitioning adalah mengurangi jumlah kasus uji (test cases) yang perlu dibuat tanpa mengurangi efektivitas pengujian dalam menemukan kesalahan. Metode ini memiliki dua kategori fungsi utama: pertama, it reduces, yang mendeskripsikan kebutuhan testing dengan jelas untuk menghindari redundansi; dan kedua, it covers a large set of other possible test cases, yang memastikan bahwa pengujian dapat mendeteksi kesalahan secara komprehensif meskipun menggunakan sampel input yang terbatas. Selain Equivalence Partitioning, metode lain yang melengkapi pengujian fungsional adalah Boundary Value Analysis (BVA). Metode ini merupakan pengembangan lebih lanjut yang berfokus pada pengujian nilai-nilai di batas (boundary) domain input, bukan hanya nilai di tengah partisi. Dijelaskan oleh (Wicaksono, 2022) bahwa metode ini didasarkan pada temuan (Myers dkk., 2004) yang menyatakan kesalahan (bugs) sering kali terjadi di ujung-ujung atau batas nilai input. Oleh karena itu, pengujian memerlukan kombinasi input yang secara spesifik menargetkan nilai batas atas dan batas bawah untuk mendeteksi kesalahan dengan lebih mudah dan akurat. Gabungan dari teknikteknik ini memberikan kerangka kerja yang kokoh untuk memastikan reliabilitas sistem yang dikembangkan.
29

II.2 Penelitian Terdahulu
Dalam upaya memperkuat landasan teoritis dan metodologis penelitian ini, penulis telah melakukan penelusuran dan kajian terhadap sejumlah penelitian terdahulu yang memiliki keterkaitan erat dengan topik perancangan website e-commerce, penerapan fitur chatbot, serta penggunaan metode Waterfall. Kajian ini bertujuan untuk memahami pendekatan, temuan, serta kontribusi dari penelitian sebelumnya yang dapat dijadikan sebagai referensi dan pembanding dalam pengembangan sistem pada penelitian ini. Rangkuman hasil kajian tersebut disajikan dalam Tabel II.5 berikut.

No

Author

Metode

Tabel II.5 Penelitian Terdahulu

Fokus Penelitian

Kekuatan

Kekurangan

Hasil Perbandingan

1 (Solecha & Waterfall Sistem Informasi Mampu mengelola data Fokus hanya pada Penelitian

ini

Yulia, 2020).

Pengelolaan Sales pesanan dan material sistem internal (back- mengembangkan sistem

Order dan Bill of secara

terstruktur, office), tidak memiliki e-commerce

yang

Material.

meningkatkan akurasi fitur e-commerce untuk menjangkau pelanggan

pengarsipan.

pelanggan, dan tidak langsung (front-end)

ada chatbot.

dengan fitur interaktif

chatbot.

30

No

Author

Metode Fokus Penelitian

Kekuatan

Kekurangan

Hasil Perbandingan

2 (Ridwan & Waterfall Rancang Bangun Menyediakan wadah Model

bisnis Penelitian ini berfokus

Fitri, 2021).

Marketplace

bagi banyak penjual marketplace tidak cocok pada e-commerce tipe

Berbasis Website. (multi-vendor) dengan untuk

perusahaan single-vendor untuk PT

fitur lengkap layaknya tunggal (single-vendor), Agro Pangan Maju

marketplace besar.

serta tidak memiliki dengan penambahan fitur

fitur layanan otomatis layanan chatbot.

(chatbot).

3 (Aisyah dkk., Waterfall E-Commerce Toko Berhasil memperluas Komunikasi dengan Penelitian ini melengkapi

2021).

Kosmetik.

jangkauan pasar dan pelanggan

masih sistem penjualan dengan

mempermudah

dilakukan

secara fitur chatbot untuk

pengelolaan data produk manual, belum ada fitur memberikan respons

secara digital.

otomatisasi layanan. real-time

kepada

pelanggan.

4 (Elita Natalia Waterfall Chatbot Virtual Efektif

membantu Chatbot terpisah dari Penelitian

ini

Sugianto dkk.,

Assistant (Ciovita). admin

menjawab website utama dan lebih mengintegrasikan chatbot

2022).

pertanyaan berulang berfungsi

sebagai langsung ke dalam

31

No

Author

Metode Fokus Penelitian

Kekuatan

Kekurangan

Hasil Perbandingan

melalui platform Line asisten

informasi website transaksi untuk

dan Telegram.

umum, bukan alat bantu memfasilitasi proses

transaksi langsung.

belanja yang mulus.

5 (Kangharnand Waterfall Mobile

E- Mengintegrasikan

Evaluasi terbatas pada Penelitian ini memperluas

o & Adiya,

Commerce Roti & chatbot dalam aplikasi Black-box

Testing evaluasi tidak hanya pada

2022).

Chatbot

penjualan

produk (fungsionalitas), belum fungsi teknis, tetapi juga

pangan,

pengujian mengukur

dampak mengukur

tingkat

akurasi jawaban chatbot chatbot

terhadap kepuasan pelanggan

mencapai 100%.

kepuasan pelanggan terhadap fitur chatbot.

secara mendalam.

6 (Cheng dkk., Survei

Respons Konsumen Memberikan wawasan Merupakan

studi Penelitian

ini

2022)

Kuantitatif terhadap Chatbot mendalam

tentang empiris (survei), bukan mengimplementasikan

Teks

faktor trust, empathy, pengembangan sistem temuan teoretis tentang

dan friendliness dalam teknis

yang chatbot ke dalam

interaksi chatbot.

menghasilkan produk pengembangan artefak

aplikasi.

sistem

nyata

32

No

Author

Metode Fokus Penelitian

Kekuatan

Kekurangan

Hasil Perbandingan

menggunakan metode

Waterfall.

7 (Ramayanti Waterfall Sistem Point of Berhasil

Tidak menyediakan Penelitian ini berfokus

dkk., 2023).

Sales (POS) & E- mengintegrasikan

fitur interaksi cerdas pada peningkatan layanan

Commerce

pencatatan penjualan (chatbot)

untuk pelanggan online melalui

toko fisik (offline) dan menangani pertanyaan chatbot, bukan pada

toko online dalam satu pelanggan

secara integrasi POS toko fisik.

sistem.

otomatis.

8 (Hussain dkk., Waterfall Chatbot dalam E- Chatbot

mampu Objek penelitian berada Penelitian ini mengadopsi

2023).

learning

mengenali pertanyaan pada domain pendidikan teknologi chatbot namun

spesifik mahasiswa dan (e-learning),

yang diterapkan pada konteks

memberikan jawaban memiliki karakteristik bisnis dan penjualan

yang relevan.

pengguna

berbeda produk pangan.

dengan e-commerce.

9 (Ningki & P, Waterfall Aplikasi Penjualan Antarmuka yang user- Fitur yang ditawarkan Penelitian

ini

2023)

Produk Tradisional friendly dan efektif standar (basic e- menambahkan

nilai

memperkenalkan

commerce),

tanpa inovasi berupa chatbot

33

No

Author

Metode Fokus Penelitian

Kekuatan

Kekurangan

Hasil Perbandingan

produk lokal ke pasar inovasi

layanan berbasis aturan (rule-

yang lebih luas.

pelanggan

seperti based)

untuk

chatbot.

meningkatkan

interaktivitas sistem.

10 (Cruzado dkk., Systematic Penggunaan

Menyajikan

bukti Hanya berupa kajian Penelitian ini merupakan

2023).

Literature Chatbot di E- teoretis kuat bahwa pustaka, tidak ada penelitian

terapan

Review Commerce

chatbot meningkatkan pengembangan sistem (applied research) yang

keterlibatan

dan atau

implementasi membangun sistem nyata

loyalitas pelanggan.

teknis yang dilakukan. berdasarkan landasan

teori tersebut.

11 (Rabani

Waterfall Chatbot Aplikasi Chatbot responsif dan Konteks bisnis adalah Penelitian ini disesuaikan

Herdiansyah

Sewa Kamera

efektif mengurangi jasa penyewaan, yang dengan alur bisnis

dkk., 2024).

beban kerja admin alur

transaksinya penjualan produk (stok,

dalam

melayani berbeda

dengan pengiriman, kedaluwarsa)

pertanyaan sewa.

penjualan

produk pada industri pangan.

ritel/pangan.

34

No

Author

Metode Fokus Penelitian

Kekuatan

Kekurangan

Hasil Perbandingan

12 (Nicholas dkk., Waterfall 2024).

13 (Yanto dkk., Waterfall 2024).

14 (Rakhmadi dkk., 2025).

Waterfall

Chatbot NLP Alat Menggunakan Natural Implementasi NLP Penelitian

ini

Elektronik

Language Processing memiliki kompleksitas menggunakan chatbot

(NLP) yang canggih dan tinggi yang mungkin berbasis aturan (rule-

mendapatkan respon berlebihan (overkill) based) yang lebih efisien

positif dari pengguna. untuk

kebutuhan dan cepat untuk

informasi

standar menangani pertanyaan

UMKM pangan.

terstruktur.

E-Commerce

Menggunakan teknologi Evaluasi utama lebih Penelitian

ini

Budaya & Chatbot Flowise AI yang modern menekankan

pada menempatkan kepuasan

AI

untuk interaksi yang efisiensi operasional, pelanggan

sebagai

lebih natural.

aspek

kepuasan variabel utama yang

pelanggan belum digali diukur

setelah

secara mendalam.

implementasi sistem.

E-Commerce

Pengujian sistem sangat Relevan (E-Commerce Penelitian ini melengkapi

Bisnis Kecil (Kue) komprehensif (Black + Waterfall + Usability). sistem sejenis dengan

Box + System Usability Kritik: Tidak ada fitur fitur chatbot dan

Scale/SUS).

Chatbot dan evaluasi mengukur dampaknya

35

No

Author

Metode Fokus Penelitian

Kekuatan

Kekurangan

Hasil Perbandingan

15 (Kusumawaty dkk., 2024).

Waterfall

menggunakan Usability terhadap

kepuasan

Scale (SUS), bukan (satisfaction), bukan

Kepuasan Pelanggan hanya usability.

(Satisfaction) yang

menjadi fokus proposal

Anda.

Chatbot

Menunjukkan bahwa Objek penelitian adalah Penelitian ini mengadopsi

Pencegahan Adiksi intervensi

chatbot kesehatan

mental, metode pengembangan

secara signifikan dapat sangat berbeda dengan chatbot-nya

namun

mengubah perilaku tujuan e-commerce yang diarahkan untuk tujuan

pengguna.

berorientasi profit.

peningkatan layanan

penjualan dan bisnis.

36

Berdasarkan hasil kajian terhadap lima belas penelitian terdahulu yang relevan dengan topik pengembangan sistem informasi berbasis web dan e-commerce, terlihat bahwa metode Waterfall menjadi pendekatan yang paling dominan digunakan. Dari keseluruhan penelitian tersebut, sebanyak tiga belas di antaranya menggunakan Waterfall sebagai metode pengembangan sistem, sementara dua lainnya menggunakan pendekatan tinjauan literatur dan survei.
Penelitian yang dilakukan oleh (Rakhmadi dkk., 2025) menunjukkan bahwa penerapan metode Waterfall pada sistem e-commerce terbukti mampu menghasilkan struktur pengembangan yang lebih terorganisir, efisien, dan terdokumentasi dengan baik. Temuan serupa juga dijelaskan oleh (Aisyah dkk., 2021), di mana metode Waterfall membantu menghasilkan sistem yang stabil serta mempermudah proses manajemen data dan transaksi penjualan secara daring.
Selain itu, (Elita Natalia Sugianto dkk., 2022) membuktikan bahwa penggunaan Waterfall dalam pengembangan aplikasi chatbot virtual assistant memungkinkan proses integrasi yang lebih sistematis pada setiap tahap pengembangan, sehingga chatbot dapat merespons pertanyaan pengguna dengan cepat dan akurat. Hasil ini diperkuat oleh penelitian (Nicholas dkk., 2024) yang menerapkan Waterfall dalam sistem chatbot berbasis Natural Language Processing (NLP) untuk mendukung layanan penjualan alat elektronik. Sistem yang dikembangkan mampu menyajikan informasi produk, stok, dan status pengiriman secara otomatis, yang berdampak pada peningkatan kenyamanan pelanggan dalam berbelanja daring. Demikian pula, penelitian (Solecha & Yulia, 2020) menunjukkan bahwa metode Waterfall mampu menghasilkan sistem administrasi penjualan dan produksi yang lebih terukur, meminimalkan kesalahan pencatatan, serta meningkatkan akurasi data pada sistem berbasis web.
Namun demikian, hasil analisis juga menunjukkan bahwa sebagian besar penelitian terdahulu masih berfokus pada aspek pengembangan sistem secara teknis tanpa mengaitkannya secara langsung dengan pengukuran kepuasan pelanggan. Beberapa penelitian seperti yang dilakukan oleh (Ningki & P, 2023) serta (Ridwan & Fitri, 2021) menitikberatkan pada pembuatan sistem e-
37

commerce untuk meningkatkan proses penjualan dan perluasan pasar, tetapi belum menambahkan fitur chatbot sebagai media interaksi pelanggan. Kondisi serupa juga ditemukan pada penelitian (Ramayanti dkk., 2023) yang hanya membahas integrasi sistem penjualan daring dan luring tanpa mengukur dampak kepuasan pengguna. Di sisi lain, penelitian (Cruzado dkk., 2023) dan (Cheng dkk., 2022) telah mengkaji dampak chatbot terhadap pengalaman pelanggan, namun belum menjelaskan secara rinci tahapan pengembangan sistem dengan metode Waterfall. Hal ini menunjukkan adanya kesenjangan antara penelitian yang berfokus pada metodologi pengembangan sistem dan penelitian yang menyoroti aspek pengalaman pengguna.
Berdasarkan hasil perbandingan dan sintesis terhadap seluruh penelitian terdahulu, dapat disimpulkan bahwa metode Waterfall merupakan pendekatan yang paling tepat untuk digunakan dalam pengembangan website e-commerce dengan fitur chatbot di PT Agro Pangan Maju. Metode ini sesuai dengan karakteristik proyek yang memiliki kebutuhan sistem yang sudah terdefinisi dengan baik dan stabil, serta memerlukan dokumentasi yang lengkap di setiap tahap pengembangan. Dengan pendekatan Waterfall, setiap fase mulai dari analisis kebutuhan, perancangan sistem, implementasi, hingga pengujian dapat dilakukan secara sistematis dan terukur. Hal ini memungkinkan perusahaan untuk memastikan kualitas dan kinerja sistem sebelum diterapkan secara menyeluruh.
Melalui hasil analisis tersebut, penelitian ini menegaskan bahwa penerapan metode Waterfall memberikan keunggulan signifikan dalam hal keteraturan proses, efisiensi dokumentasi, dan keandalan hasil sistem. Dengan mengintegrasikan fitur chatbot ke dalam website e-commerce dan menjadikannya sebagai objek pengukuran Kepuasan Pelanggan, penelitian ini tidak hanya berkontribusi pada pengembangan sistem informasi yang stabil dan efisien, tetapi juga menghadirkan inovasi yang mampu meningkatkan kepuasan pelanggan melalui layanan yang cepat, informatif, dan terotomatisasi. Oleh karena itu, penelitian ini diharapkan dapat menjadi model pengembangan yang komprehensif bagi perusahaan berskala besar dalam menerapkan transformasi digital yang terstruktur dan berorientasi pada pelanggan.
38

II.3 Perbandingan Metode

Dalam perancangan sistem informasi e-commerce dan chatbot, pemilihan model pengembangan perangkat lunak (Software Development Life Cycle) merupakan faktor determinan yang mempengaruhi kualitas akhir serta keberhasilan implementasi sistem. Setiap metode pengembangan memiliki karakteristik spesifik yang menawarkan kelebihan maupun keterbatasan, bergantung pada dinamika kebutuhan operasional, skala kompleksitas, serta tingkat fleksibilitas yang dibutuhkan selama proses pengerjaan. Oleh karena itu, diperlukan analisis komparatif untuk menempatkan metode yang dipilih dalam perspektif yang tepat. Berikut disajikan tabel perbandingan antara metode Waterfall, Agile (Scrum), dan Prototyping guna memberikan gambaran komprehensif mengenai alasan pemilihan metode dalam penelitian ini.

Kategori Stabilitas Kebutuhan
Fleksibilitas

Tabel II.6 Perbandingan Metode

Waterfall

Agile (Scrum)

- Cocok untuk kebutuhan yang jelas dan - Cocok untuk kebutuhan yang

tetap (fixed) sejak awal. Aturan bisnis

samar dan berubah-ubah

tidak boleh berubah di tengah jalan.

(dynamic) mengikuti tren pasar.

- Rendah (Kaku). Perubahan di tahap lanjut sulit dilakukan dan berbiaya mahal karena harus mengulang fase sebelumnya.

- Sangat Tinggi. Perubahan fitur disambut baik kapan saja, bahkan di akhir pengembangan.

Protoyping - Cocok untuk kebutuhan yang
belum diketahui, digali sambil jalan melalui contoh desain. - Sedang. Fleksibel pada antarmuka (interface), namun logika sistem inti bisa terganggu jika revisi berlebihan.

39

Kategori

Waterfall

Agile (Scrum)

Protoyping

- Lengkap & Terstruktur. Dokumentasi - Minim. Lebih mengutamakan

- Fokus Visual. Dokumentasi

dibuat detail di setiap fase (documentDokumentasi
driven). Sangat sesuai untuk standar

fungsi aplikasi daripada kelengkapan dokumen (working

cenderung berfokus pada evolusi desain, aspek teknis kode sering

laporan skripsi.

software over documentation).

kurang terdokumentasi.

Keterlibatan User

- Terjadwal (Awal & Akhir). User hanya sibuk saat wawancara awal dan testing akhir. Tidak mengganggu operasional harian mitra.

- Intensif (Terus-menerus). User wajib terlibat setiap hari/minggu untuk review. Sulit jika klien sibuk.

- Aktif (Saat Evaluasi). User harus meluangkan waktu berulang kali untuk mengecek mockup desain.

Waktu & Jadwal

- Terprediksi (Pasti). Jadwal selesai (deadline) bisa ditentukan akurat sejak awal karena lingkup kerja dikunci.

- Tidak Tentu. Waktu selesai sulit diprediksi jika fitur terus bertambah (scope creep).

- Cepat di Awal. Cepat menghasilkan tampilan, tapi penyelesaian sistem utuh bisa lama jika revisi tak terkendali.

Kesesuaian Sistem

- Ideal untuk sistem B2B / Stok / Keuangan yang aturannya ketat dan baku.

- Ideal untuk aplikasi Startup / Sosmed yang butuh inovasi cepat.

- Ideal untuk aplikasi yang mengutamakan Estetika / User Interface.

40

Berdasarkan hasil komparatif pada tabel di atas, dapat disimpulkan bahwa Waterfall merupakan metode yang paling tepat untuk diterapkan dalam penelitian ini. Pemilihan ini didasarkan pada karakteristik operasional PT Agro Pangan Maju sebagai perusahaan distributor B2B yang memiliki alur bisnis terstandarisasi dan aturan manajemen stok yang bersifat tetap (fixed). Metode Waterfall menonjol melalui pendekatan linearnya yang sistematis, memastikan bahwa setiap kebutuhan sistem seperti logika penentuan harga berjenjang dan mekanisme respons chatbot telah didefinisikan secara matang pada tahap analisis sebelum melangkah ke tahap teknis. Hal ini sangat krusial untuk mencegah terjadinya kesalahan logika pada fitur sinkronisasi stok yang menjadi fokus utama permasalahan perusahaan.
Selain itu, Waterfall memberikan jaminan terhadap kualitas dokumentasi teknis yang komprehensif pada setiap fasenya. Dalam konteks penelitian akademis dan pengembangan sistem jangka panjang bagi perusahaan, ketersediaan dokumen perancangan yang lengkap (mulai dari Use Case hingga skema Database) menjadi aset vital untuk pemeliharaan sistem di masa depan. Hal ini berbeda dengan pendekatan lain yang cenderung memprioritaskan kecepatan rilis fitur dibandingkan kelengkapan administrasi sistem.
Sebaliknya, metode lain dinilai kurang relevan dengan batasan dan kebutuhan penelitian ini. Metode Agile (Scrum), meskipun menawarkan fleksibilitas tinggi, menuntut keterlibatan pengguna (user) yang sangat intensif dan perubahan fitur yang dinamis, yang mana hal ini kurang sesuai dengan ketersediaan waktu mitra bisnis serta alur kerja B2B yang kaku. Sementara itu, Metode Prototyping memiliki risiko scope creep (perluasan lingkup kerja) karena siklus revisi antarmuka yang berpotensi tidak berkesudahan, yang dapat mengalihkan fokus pengembangan dari fungsionalitas utama manajemen stok. Dengan mempertimbangkan kebutuhan akan stabilitas sistem, kejelasan alur bisnis sejak awal, serta tuntutan penyusunan laporan skripsi yang terstruktur, metode Waterfall dinilai sebagai pilihan yang paling realistis dan efektif untuk menyelesaikan permasalahan di PT Agro Pangan Maju.
41

BAB III METODE PENELITIAN
Bab ini menguraikan kerangka kerja metodologis dan strategi sistematis yang diadopsi untuk menjawab permasalahan penelitian di PT Agro Pangan Maju. Fokus pembahasan meliputi penetapan metode pengembangan perangkat lunak yang dijadikan acuan utama dalam merancang sistem e-commerce terintegrasi. Selain itu, bab ini juga menjabarkan rincian tahapan penyelesaian masalah secara komprehensif, mulai dari strategi pengumpulan data lapangan, mekanisme pengolahan kebutuhan sistem, hingga prosedur evaluasi yang diterapkan untuk memvalidasi fungsi dari fitur chatbot yang dikembangkan.
III.1 Metode Penelitian Penelitian ini dilaksanakan menggunakan metode kualitatif dengan pendekatan studi kasus (case study). Strategi studi kasus dipilih karena penelitian berfokus pada eksplorasi mendalam terhadap fenomena spesifik dalam batasan konteks nyata, yaitu permasalahan operasional penjualan di PT Agro Pangan Maju. Melalui pendekatan ini, peneliti menggali data secara komprehensif untuk memahami bagaimana ketidakefisienan proses bisnis terjadi, mengidentifikasi hambatan komunikasi antar-divisi, serta memetakan kebutuhan sistem yang sebenarnya berdasarkan perspektif para pemangku kepentingan (stakeholders) di perusahaan tersebut.
Sebagai kerangka kerja teknis untuk menerjemahkan hasil analisis kasus tersebut, penelitian ini mengadopsi metode pengembangan sistem Waterfall atau siklus hidup klasik. Pemilihan metode Waterfall didasarkan pada karakteristik temuan studi kasus yang menunjukkan bahwa kebutuhan sistem seperti integrasi pembayaran digital dan fitur respons otomatis telah terdefinisi dengan jelas (fixed requirements) dan memiliki alur logika yang terstruktur. Metode ini memfasilitasi proses pembangunan perangkat lunak yang sistematis, dimulai dari analisis kebutuhan yang matang hingga tahap implementasi, sehingga meminimalisir risiko perubahan lingkup di tengah proses pengembangan.
42

Melalui perpaduan ini, pendekatan studi kasus berfungsi sebagai fondasi untuk memastikan kebenaran akar masalah, sementara metode Waterfall berperan sebagai panduan teknis pembangunan sistem. Tujuannya adalah agar aplikasi yang dihasilkan tidak hanya berjalan dengan baik secara fungsi, tetapi juga benarbenar relevan dalam menyelesaikan kendala operasional penjualan di PT Agro Pangan Maju. III.2 Sistematika Penyelesaian Masalah Penyusunan sistematika penyelesaian masalah merupakan langkah fundamental dalam memastikan bahwa seluruh tahapan penelitian berjalan secara logis, terukur, dan dapat dipertanggungjawabkan secara ilmiah. Dalam rangka menghasilkan solusi teknologi yang tepat guna bagi PT Agro Pangan Maju, penelitian ini menerapkan kerangka kerja sistematis yang mengadopsi prinsip pengembangan perangkat lunak terstruktur. Pendekatan ini dirancang untuk memandu peneliti melewati setiap fase kritis, mulai dari eksplorasi mendalam terhadap akar permasalahan operasional, penerjemahan kebutuhan bisnis menjadi spesifikasi teknis, hingga realisasi dan validasi sistem akhir. Dengan adanya pedoman yang jelas ini, proses transformasi dari identifikasi masalah konvensional menuju implementasi solusi digital dapat dilakukan secara berkesinambungan dan minim risiko. Adapun gambaran visual yang merepresentasikan keseluruhan alur penyelesaian masalah tersebut disajikan secara rinci dalam diagram alir pada Gambar III.1 berikut ini:
43

Gambar III.1 Sistematika Penyelesaian Masalah Menggunakan Metode Waterfall (Pressman, 2015)
Berdasarkan diagram alir di atas, berikut adalah uraian mendalam mengenai aktivitas yang dilakukan pada setiap tahapan penelitian:
III.2.1 Identifikasi Masalah Penelitian ini diawali dengan tahap identifikasi masalah untuk memahami kondisi aktual yang terjadi di lapangan. Pada fase ini, penulis melakukan penelusuran awal terhadap gejala-gejala inefisiensi yang muncul dalam proses bisnis perusahaan. Fokus utama kegiatan ini adalah membedah kendala operasional yang diakibatkan oleh ketergantungan pada proses pengelolaan data yang masih terfragmentasi dan belum terintegrasi antar divisi. Identifikasi ini bertujuan untuk merumuskan akar permasalahan yang sesungguhnya, yaitu ketiadaan sistem sentral yang mampu menjembatani transaksi penjualan, pengelolaan stok gudang, dan pencatatan keuangan secara real-time.
III.2.2 Pengumpulan Data Setelah masalah teridentifikasi, langkah selanjutnya adalah mengumpulkan data pendukung yang relevan secara komprehensif. Penulis melakukan pengumpulan data primer melalui wawancara mendalam dengan pemangku kepentingan utama, yakni Chief Operating Officer (COO) dan staf operasional, untuk mendapatkan gambaran detail mengenai alur kerja yang
44

berjalan. Selain itu, data sekunder juga dihimpun berupa dokumen laporan keuangan dan catatan stok fisik perusahaan. Data-data ini berfungsi sebagai bukti empiris untuk memvalidasi skala usaha perusahaan dan menjustifikasi kebutuhan akan sistem yang lebih robust dibandingkan metode pencatatan konvensional yang digunakan saat ini.
III.2.3 Analisis Perencanaan Teknis Data yang telah terkumpul kemudian diolah pada tahap analisis dan perencanaan teknis. Penulis memetakan kebutuhan fungsional sistem yang akan dibangun, mencakup pendefinisian fitur-fitur krusial seperti mekanisme respon otomatis melalui chatbot, logika validasi pembayaran digital, serta perhitungan biaya pengiriman otomatis. Bersamaan dengan itu, dilakukan perencanaan sumber daya teknis yang meliputi penetapan bahasa pemrograman, pemilihan kerangka kerja (framework), serta penentuan layanan API pihak ketiga (Midtrans dan RajaOngkir) yang akan diintegrasikan. Tahap ini menghasilkan spesifikasi kebutuhan perangkat lunak yang menjadi acuan pengembangan.
III.2.4 Perancangan Arsitektur Sistem Memasuki area teknis, aktivitas difokuskan pada perancangan arsitektur sistem untuk menerjemahkan hasil analisis kebutuhan menjadi cetak biru aplikasi. Penulis merancang struktur logika sistem menggunakan pemodelan visual untuk menggambarkan interaksi antar pengguna dan sistem. Hal ini meliputi pembuatan diagram alur data, skema basis data relasional untuk menjamin integritas penyimpanan data transaksi, serta desain antarmuka pengguna (user interface) yang ergonomis. Rancangan ini disusun secara detail agar dapat menjadi panduan yang jelas pada saat proses penulisan kode program dilakukan.
III.2.5 Pembangunan & Integrasi Sistem Rancangan yang telah disepakati selanjutnya direalisasikan pada tahap pembangunan dan integrasi sistem. Pada fase ini, penulis melakukan penulisan kode program (coding) untuk membangun aplikasi e-commerce berbasis web.
45

Aktivitas pengembangan tidak hanya terbatas pada pembuatan halaman antarmuka, tetapi juga mencakup pembangunan logika chatbot dan integrasi sistem dengan layanan pihak ketiga (API) untuk gerbang pembayaran dan logistik. Seluruh komponen sistem disatukan dan diuji secara internal (unit testing) untuk memastikan setiap fungsi berjalan dengan baik dan bebas dari kesalahan sintaksis.
III.2.6 Validasi & Penyerahan Rangkaian penelitian bermuara pada tahap validasi dan penyerahan sistem. Sistem yang telah selesai dikembangkan diuji kinerjanya untuk memastikan kesesuaian dengan spesifikasi kebutuhan awal. Evaluasi dilakukan baik secara teknis menggunakan Black Box Testing untuk memverifikasi fungsi fitur, maupun melalui pengujian langsung oleh pengguna di PT Agro Pangan Maju (User Acceptance Testing) guna mengukur tingkat keberterimaan dan kepuasan terhadap sistem. Setelah sistem dinyatakan valid, dilakukan proses serah terima aplikasi kepada perusahaan untuk diimplementasikan sebagai solusi operasional.
III.3 Metode Pengumpulan Data dan Pengolahan Data
III.3.1 Metode Pengumpulan Data Pengumpulan data dalam penelitian ini dilakukan untuk memperoleh pemahaman yang mendalam dan menyeluruh mengenai proses bisnis, kendala operasional, serta kebutuhan fungsional sistem yang akan dirancang pada PT Agro Pangan Maju. Data yang akurat sangat diperlukan untuk memastikan solusi e-commerce dan chatbot yang dibangun dapat menjawab permasalahan secara tepat. Adapun metode pengumpulan data yang digunakan adalah sebagai berikut:
1. Wawancara (Interview) Pengumpulan data melalui metode wawancara dilaksanakan secara langsung pada tanggal 22 September 2025 dengan melibatkan pemangku kepentingan strategis yang memiliki otoritas penuh terhadap operasional perusahaan. Dalam pelaksanaannya, peneliti melakukan sesi tanya jawab
46

mendalam bersama Bapak Budi Santoso selaku Chief Operating Officer (COO) guna memetakan alur bisnis penjualan eksisting, mengidentifikasi hambatan integrasi data antar divisi, serta memvalidasi urgensi kebutuhan transformasi digital di perusahaan. Berdasarkan hasil wawancara tersebut, terungkap bahwa mekanisme penjualan yang berjalan saat ini masih sangat bergantung pada pengelolaan data menggunakan spreadsheet yang terpisah di masing-masing divisi serta komunikasi berbasis aplikasi pesan singkat. Kondisi operasional yang terfragmentasi ini mengakibatkan proses rekapitulasi data membutuhkan waktu yang cukup lama serta meningkatkan risiko terjadinya selisih (discrepancy) stok karena informasi persediaan tidak terbarui secara real-time. 2. Observasi (Observation) Observasi dilakukan melalui pengamatan langsung terhadap alur kerja operasional yang berjalan di lingkungan PT Agro Pangan Maju. Fokus pengamatan diarahkan pada mekanisme koordinasi lintas divisi meliputi admin penjualan, bagian gudang, dan keuangan saat memproses pesanan pelanggan, serta meninjau pemanfaatan alat bantu kerja berbasis spreadsheet (Excel) dan aplikasi pesan instan (WhatsApp) yang digunakan untuk verifikasi ketersediaan barang maupun konfirmasi pembayaran. Selain itu, peneliti juga mengamati tata cara penanganan pertanyaan pelanggan terkait informasi stok dan harga yang sepenuhnya dikelola oleh admin. Hasil observasi memperkuat temuan bahwa ketiadaan sistem yang terintegrasi dan terpusat menyebabkan waktu pelayanan menjadi kurang optimal serta meningkatkan potensi ketidakakuratan data dalam pemrosesan transaksi harian. 3. Studi Dokumentasi (Document Review) Studi dokumentasi dilakukan melalui peninjauan terhadap arsip dan dokumen internal perusahaan yang relevan untuk mendukung validitas data serta perancangan sistem. Fokus penelaahan diarahkan pada Laporan Keuangan Laba Rugi Standar Periode 2024, yang mencatatkan total pendapatan penjualan sebesar Rp 63,7 miliar. Data faktual ini tidak hanya mengukuhkan status PT Agro Pangan Maju sebagai entitas usaha berskala
47

besar, tetapi juga menjustifikasi kebutuhan akan infrastruktur sistem yang mumpuni (robust) untuk mengakomodasi volume transaksi tersebut. Selain itu, peneliti juga menganalisis format laporan penjualan dan pencatatan stok yang sedang berjalan (eksisting) sebagai acuan dasar dalam merancang struktur basis data agar hasil keluaran sistem tetap selaras dengan standar pelaporan manajemen. Melalui triangulasi data dari wawancara, observasi, dan studi dokumentasi ini, peneliti memperoleh pemahaman komprehensif yang menjadi landasan kuat untuk merancang aplikasi e-commerce terintegrasi dengan fitur chatbot yang mampu mengoptimalkan kinerja operasional perusahaan. III.3.2 Metode Pengolahan Data Pada tahapan pengolahan data, penelitian ini menerapkan pendekatan analisis kualitatif deskriptif untuk memetakan dan mengevaluasi alur kerja operasional yang sedang berjalan. Data mentah yang diperoleh dari hasil wawancara, observasi, dan studi dokumentasi dianalisis secara mendalam guna memahami logika bisnis, mengidentifikasi titik kemacetan (bottleneck), serta merumuskan kebutuhan sistem yang solutif. Langkah fundamental dalam proses ini dimulai dengan memvisualisasikan mekanisme penjualan saat ini (As-Is) untuk mendeteksi secara presisi letak kekurangan yang terjadi di lapangan. Visualisasi alur kerja tersebut disajikan dalam bentuk diagram Business Process Model and Notation (BPMN) sebagaimana terlihat pada Gambar III.2 berikut ini:
48

Gambar III.2 Proses Bisnis AS-IS PT Agro Pangan Maju
Berdasarkan pemetaan proses bisnis berjalan pada Gambar III.2, terlihat bahwa alur penjualan di PT Agro Pangan Maju masih memiliki ketergantungan yang tinggi pada komunikasi langsung antar-divisi yang menyebabkan pelayanan menjadi lambat. Proses bermula ketika Admin Penjualan menerima pesanan namun tidak memiliki akses langsung terhadap data stok aktual, sehingga diwajibkan bertanya langsung kepada Bagian Gudang (melalui pesan singkat/telepon) untuk memastikan ketersediaan barang fisik. Mekanisme pengecekan ganda ini menciptakan penundaan waktu yang cukup lama
49

sebelum pelanggan mendapatkan kepastian pesanan. Hambatan serupa berlanjut pada tahap validasi pembayaran, di mana bukti transaksi dari pelanggan harus dipindahkan satu per satu dari Admin Penjualan ke Bagian Keuangan untuk dicocokkan dengan mutasi bank. Rangkaian aktivitas yang terpecah-pecah ini, ditambah dengan pembuatan dokumen administrasi seperti faktur dan surat jalan yang masih mengandalkan aplikasi perkantoran pengolah kata dan angka (seperti Excel/Word), mengakibatkan siklus transaksi menjadi panjang serta tingginya risiko kesalahan pencatatan (human error) yang berdampak langsung pada penurunan kualitas layanan pelanggan. Berangkat dari kendala proses kerja yang lambat dan data yang masih terpisahpisah tersebut, penelitian ini selanjutnya merumuskan rancangan ulang proses bisnis sebagai solusi perbaikan. Hasil analisis masalah di atas menjadi landasan utama dalam penyusunan spesifikasi kebutuhan sistem untuk merancang proses bisnis usulan (To-Be). Rancangan proses baru ini difokuskan pada integrasi seluruh aktivitas penjualan ke dalam satu ekosistem digital terpusat berbasis website dan chatbot, yang bertujuan untuk mengotomatisasi validasi stok serta pembayaran guna meningkatkan kecepatan dan akurasi operasional perusahaan secara menyeluruh. Visualisasi dari rancangan ulang proses bisnis tersebut digambarkan secara rinci melalui diagram Business Process Model and Notation (BPMN) pada Gambar III.3 di bawah ini:
50

Gambar III.3 Proses Bisnis TO-BE Usulan PT Agro Pangan Maju Mengacu pada Gambar III.3, alur proses bisnis usulan (To-Be) dirancang untuk menyederhanakan alur komunikasi yang sebelumnya menghambat kinerja perusahaan. Perubahan fundamental terlihat pada mekanisme pengecekan stok dan validasi transaksi yang kini sepenuhnya diambil alih oleh sistem secara otomatis (system-driven). Dalam alur baru ini, pelanggan diberikan keleluasaan melalui metode belanja mandiri dan terpandu, di mana mereka dapat memilih
51

untuk menelusuri katalog produk sendiri atau berinteraksi dengan fitur Chatbot cerdas untuk mendapatkan rekomendasi produk. Kedua jalur tersebut terhubung langsung dengan basis data pusat, sehingga informasi ketersediaan barang yang diterima pelanggan dijamin akurat tanpa perlu menunggu konfirmasi dari staf gudang.
Peningkatan efisiensi juga diterapkan pada tahapan pembayaran dan logistik. Peran admin dalam memverifikasi bukti transfer satu per satu telah digantikan oleh integrasi gerbang pembayaran (payment gateway) yang mampu memvalidasi status pelunasan secara seketika (real-time). Apabila pembayaran terkonfirmasi sukses, sistem akan secara otomatis mengurangi stok di basis data dan menerbitkan dokumen pengiriman digital langsung ke dashboard Bagian Gudang. Dengan mekanisme ini, staf gudang dapat segera memproses pengepakan barang berdasarkan notifikasi sistem, tanpa harus melewati proses oper-operan dokumen yang berisiko memperlambat pengiriman. Transformasi alur ini memastikan bahwa seluruh siklus penjualan, mulai dari pemesanan hingga penyerahan barang ke ekspedisi, berjalan dalam satu tarikan garis lurus yang efisien dan transparan.
III.4 Metode Evaluasi Evaluasi sistem dilakukan untuk memastikan bahwa aplikasi e-commerce dan fitur chatbot yang dibangun telah sesuai dengan kebutuhan pengguna dan dapat berfungsi secara optimal dalam mendukung proses optimalisasi penjualan pada PT Agro Pangan Maju. Evaluasi dilakukan dengan mengacu pada user requirement yang diperoleh pada tahap analisis, yang meliputi kebutuhan pengguna dalam proses pemesanan produk, pengecekan ongkos kirim otomatis, pembayaran digital, serta layanan informasi pelanggan melalui chatbot. Acuan evaluasi ditentukan berdasarkan kebutuhan empat kelompok pengguna sistem, yaitu Pelanggan, Admin Penjualan, Tim Gudang, dan Bagian Keuangan, sehingga setiap hasil pengujian dapat dinilai dari perspektif pelaku utama operasional bisnis.
52

1. Black-Box Testing Metode Black-Box Testing digunakan untuk menguji kesesuaian fungsi sistem berdasarkan input dan output tanpa memeriksa struktur kode internal. Pengujian dilakukan melalui skenario penggunaan nyata oleh Pelanggan, Admin, dan Tim Gudang sesuai dengan hak akses mereka. Skenario pengujian mencakup proses tanya jawab stok dengan chatbot, kalkulasi biaya kirim via API RajaOngkir, validasi pembayaran via gateway Midtrans, hingga pembaruan status stok di gudang. Acuan keberhasilan pengujian ditentukan oleh apakah hasil keluaran fitur sistem telah sesuai dengan kebutuhan fungsional yang ditetapkan dalam functional requirement specification dan apakah sistem mampu menangani berbagai variasi data transaksi tanpa kesalahan (error).
2. User Acceptance Testing (UAT) Evaluasi tahap akhir dilakukan menggunakan metode User Acceptance Testing (UAT) untuk memvalidasi bahwa sistem dapat diterima oleh pengguna akhir dan mendukung alur bisnis operasional yang sesungguhnya. Proses evaluasi ini melibatkan Bapak Budi Santoso selaku Chief Operating Officer (COO) sebagai pemangku kepentingan utama, serta Admin Penjualan dan Tim Operasional sebagai pihak yang terlibat langsung dalam pengelolaan transaksi harian. Selain itu, evaluasi juga melibatkan sampel Pelanggan untuk menilai aspek kepuasan terhadap responsivitas chatbot dan kemudahan alur belanja. Sistem dinyatakan valid dan diterima apabila para pihak tersebut memberikan penilaian bahwa sistem telah terbukti membantu meningkatkan kinerja waktu pemrosesan pesanan, akurasi data stok antardivisi, serta kecepatan pelayanan pelanggan dibandingkan sistem sebelumnya.
53

DAFTAR PUSTAKA
1. Aisyah, S., Amelia, R., Sabrina, I., & Syahrifah, T. (2021). PERANCANGAN APLIKASI E-COMMERCE PADA TOKO LILI DENGAN PENERAPAN METODE WATERFALL. Jurnal Sistem Informasi dan Ilmu Komputer Prima), 5(1).
2. Arsyadin, A. (2025). Analisis Teks Respon Otomatis Chatbot Provider Internet dan Telepon Seluler Menggunakan Karakter Perempuan. Jurnal Pewarta Indonesia, 7(1), 66­79. https://doi.org/10.25008/jpi.v7i1.189
3. Badan Pusat Statistik. (2025). Statistik E-Commerce 2023 (Vol. 6). Dalam Badan Pusat Statistik.
4. Booch, Grady., Rumbaugh, James., & Jacobson, Ivar. (2005). The unified modeling language user guide. Addison-Wesley.
5. Cheng, X., Bao, Y., Zarifis, A., Gong, W., & Mou, J. (2022). Exploring consumers' response to text-based chatbots in e-commerce: the moderating role of task complexity and chatbot disclosure. Internet Research, 32(2), 496­517. https://doi.org/10.1108/INTR-08-2020-0460
6. Cruzado, J. G., Arévalo, A. A., Gamboa-Cruzado, J., Menendez-Morales, C., Franco, C., Carpio, D., López-Goycochea, J., Vargas, C. R., & Villarreal, F. (2023). USE OF CHATBOTS IN E-COMMERCE: A COMPREHENSIVE SYSTEMATIC REVIEW. Article in Journal of Theoretical and Applied Information Technology, 28(4). https://www.researchgate.net/publication/391111777
7. Dennis, Alan., Wixom, B. Haley., & Roth, R. M. . (2012). System analysis and design. John Wiley.
8. Dennis, Alan., Wixom, B. Haley., Tegarden, D. Paul., & Seeman, Elaine. (2015). System analysis & design: an object-oriented approach with UML. Wiley.
9. Elita Natalia Sugianto, Jessica Aurelia Sujangga, Delvia, N., Verdiana Ayustika, & Agus Cahyo Nugroho. (2022). Pengembangan Chatbot "Ciovita" Virtual Assistant Cioccolato Brownie Semarang Dengan Metode Waterfall. Journal of Applied Computer Science and Technology, 3(2), 179­185. https://doi.org/10.52158/jacost.v3i2.348
54

10. Ghezzi, Carlo., Jazayeri, Mehdi., & Mandrioli, Dino. (2003).

Fundamentals of software engineering. Prentice Hall.

11. Google, Temasek, & Bain & Company. (2024). e-Conomy SEA 2024

Report.

12. Hidayat, M., Faadihillah Wahid, Z., & Gilang Nauri, M. R. (2025).

Pemanfaatan Teknologi Chatbot pada E-Commerce untuk Mendukung

Pemasaran dan Penjualan Kerupuk Emplek-Emplek. SEMINAR

NASIONAL

TEKNOLOGI

DAN

SAINS,

4.

https://doi.org/https://doi.org/10.29407/d051rz21

13. Hussain, S., Al-Hashmi, S. H., Malik, M. H., & Ali Kazmi, S. I. (2023).

Chatbot in E-learning. SHS Web of Conferences, 156, 01002.

https://doi.org/10.1051/shsconf/202315601002

14. Kangharnando, W., & Adiya, H. (2022). Penerapan Teknologi Chatbot

Pada Website Mobile E-Commerce Roti Papa. Jurnal Mahasiswa Aplikasi

Teknologi Komputer dan Informasi, 4, 1­7.

15. Kendall, K. E. ., & Kendall, J. E. . (2011). Systems analysis and design.

Pearson Prentice Hall.

16. Kotler, Philip., & Keller, K. Lane. (2016). Marketing management.

Pearson.

17. Kusumawaty, I., Yunike, Fadly, & Kurniawan, T. B. (2024). Development

and Evaluation of an AI-Based Chatbot for Preventing Social Media

Addiction: A Waterfall Model Approach. Journal of Advanced Research

in Applied Sciences and Engineering Technology, 56(2), 1­13.

https://doi.org/10.37934/araset.56.2.113

18. Laudon, K. C. ., & Traver, C. Guercio. (2021). E-commerce: business,

technology, society. Pearson Education Limited.

19. Leach, R. J. . (2016). Introduction to software engineering. CRC Press.

20. Mishra, R. (2012). Software Engineering (DCAP405). EXCEL BOOKS

PRIVATE LIMITED.

21. Myers, G. J. ., Badgett, Tom., Thomas, T. M. ., & Sandler, Corey. (2004).

The Art of Software Testing. John Wiley & Sons.

55

22. Nicholas, A., Duha, Y., Kunci Chatbot, K., Penjualan, S., Elektronik, A., & Korespondensi, B. (2024). Penerapan Chatbot Berbasis NLP Pada Sistem Penjualan Alat Elektronik dan Bangunan. Jurnal Mahasiswa Aplikasi Teknologi Komputer dan Informasi, 6(3), 167­175.
23. Ningki, C., & P, N. (2023). Implementasi Aplikasi Penjualan Produk Tradisional Berbasis Website Menggunakan Metode Waterfall. Informatik: Jurnal Ilmu Komputer, 19(2), 107­114. https://doi.org/10.52958/iftk.v19i2.6149
24. Nur Rais, A., Firmansyah, M., Puri Dwi Setyawan, V., Azizah, N., Munawaroh, S., Ayu Fatmawati, D., & Faizal Amir, R. (2022). Implementasi Sistem Informasi Food and Beverage Online Shop Dengan Metode Waterfall Yang Dimodifikasi. Jurnal Sains dan Manajemen, 10(1).
25. O'Brien, J. A. ., & Marakas, G. M. . (2010). Introduction to information systems. McGraw-Hill Irwin.
26. Pressman, R. S. . (2015). Software engineering: a practitioner's approach. McGraw-Hill Education.
27. Purwaningtias, D., Nasihin, M., & Arizona, N. D. (2020). E-BUSINESS; KONSEP DASAR E-BUSINESS DI ERA DIGITAL (1 ed.).
28. Rabani Herdiansyah, H., Voutama Sistem Informasi, A., Singaperbangsa Karawang Jl HSRonggo Waluyo, U., Timur, T., & Barat, J. (2024). IMPLEMENTASI CHATBOT PADA APLIKASI SEWA KAMERA BERBASIS WEBSITE MENGGUNAKAN METODE WATERFALL. Jurnal Mahasiswa Teknik Informatika, 8(4).
29. Rakhmadi, A., Firdaus, M. I., & Nugroho, S. (2025). Computing and INformation Systems Journal A Systematic Implementation of the Waterfall Model in E-Commerce System Development for Small Businesses. COINS Journal, 1(1). www.coins-indocompt.org
30. Ramayanti, D., Jumaryadi, Y., Sunandar, A., & Kolidi, K. (2023). Implementasi Metode Waterfall dalam Pengembangan Sistem Point of Sales dan e-Commerce. TIN: Terapan Informatika Nusantara, 4(2), 129­ 133. https://doi.org/10.47065/tin.v4i2.4208
56

31. Ridwan, M., & Fitri, I. (2021). Rancang Bangun Marketplace Berbasis

Website menggunakan Metodologi Systems Development Life Cycle

(SDLC) dengan Model Waterfall. Jurnal Teknologi Informasi dan

Komunikasi), 5(2), 2021. https://doi.org/10.35870/jti

32. Sahata Sitanggang, A., Syafariani, R. F., Sari, F. W., Wartika, W., & Hasti,

N. (2023). Relation of Chatbot Usage Towards Customer Satisfaction

Level in Indonesia. International Journal of Advances in Data and

Information

Systems,

4(1),

86­96.

https://doi.org/10.25008/ijadis.v4i1.1261

33. Santoso, B. (2025). Transkrip Wawancara Dengan Narasumber PT

AGRO PANGAN MAJU.

34. Solecha, K., & Yulia, E. R. (2020). Rancang Bangun Sistem Informasi

Pengelolaan Sales Order dan Bill of Material Interior dan Exterior

Bangunan Menggunakan Model Waterfall. Jurnal Teknologi Informasi, 6.

http://ejournal.urindo.ac.id/index.php/TI

35. Spillner, Andreas., Linz, Tilo., & Schaefer, H. . (2014). Software testing

foundations: a study guide for the certified tester exam. Rocky Nook.

36. Wicaksono, S. (2022). Blackbox Testing Teori dan Studi Kasus.

https://doi.org/10.5281/zenodo.7659674

37. Wijoyo, H., Ariyanto, A., Sudarsono, A., & Wijayanti, K. D. (2021).

SISTEM INFORMASI MANAJEMEN (M. F. Akbar, Ed.). INSAN

CENDEKIA MANDIRI.

38. Yanto, G., Puspita, S., & Efendy, Z. (2024). Inovasi E-Commerce Berbasis

Chatbot AI Pada Deta Rancak dan Tingkuluak Kreasi Sanggar Seni

Permata Hati. JISTech (Journal of Islamic Science and Technology), 9(2),

233. https://doi.org/10.30829/jistech.v9i2.22768

57

LAMPIRAN
Lampiran 1 Foto Wawancara Dengan Narasumber
58

59

Lampiran 2 Laporan Laba Rugi PT Agro Pangan Maju
60

Lampiran 3 Laporan Penjualan Menggunakan Excel
61

62

63

Lampiran 4 Transkrip Wawancara Dengan Narasumber
64

Lampiran 5 Gambar Proses Bisnis AS ­ IS PT Agro Pangan Maju
65

Lampiran 6 Gambar Proses Bisnis To ­ Be PT Agro Pangan Maju
66

