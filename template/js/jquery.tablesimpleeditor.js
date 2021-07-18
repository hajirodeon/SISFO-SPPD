

/**
 * jlSimpleTableEditor adalah sebuah widget jQuery UI yang berfungsi untuk menyulap sebuah
 * table HTML biasa menjadi tabel yang dapat di-edit.
 *
 * Kode program ini dibuat oleh TheSolidSnake dan dapat di-download secara gratis di
 * situs http://thesolidsnake.wordpress.com.
 *
 */
(function($) {

$.widget("thesolidsnake.jlSimpleTableEditor", {

        options: {
                debug: false
        },

        _create: function() {

                // membuat <input type="hidden"> untuk setiap baris data dan menambahkan tombol "edit", bila ada data.
                var baris = this.element.find("tbody tr");
                for (var indexBaris = 0; indexBaris < baris.length; indexBaris++) {
                        var kolom = $(baris[indexBaris]).find("td");
                        for (var indexKolom=0; indexKolom<kolom.length; indexKolom++) {
                                this._buatHiddenInput(indexBaris, indexKolom, kolom[indexKolom], this.element);
                        }
                        this._buatTombolEdit(indexBaris, baris[indexBaris]);
                        this._buatTombolHapus(indexBaris, baris[indexBaris]);
                }

                // counter untuk mengetahui jumlah baris
                $("<input type='hidden' name='jumlahBaris' id='jumlahBaris' />").val(baris.length).appendTo(this.element);

                // bila ada <thead>, tambahkan sebuah dummy kolom header baru (untuk menjaga konsistensi HTML).
                var thead = this.element.find("thead tr");
                if (thead.length>0) {
                        $("<th>&nbsp;</th>").appendTo(thead);  // untuk tombol Edit
                        $("<th>&nbsp;</th>").appendTo(thead);  // untuk tombol Hapus
                }

                // membuat tombol Tambah bila perlu dan melakukan binding event
                var idTombolTambah = this.options.idTombolTambah;
                if (idTombolTambah===undefined) {
                        idTombolTambah = this.element.attr("id") + "-tombol-tambah";
                        this.element.before($("<input type='button'/>").val("Tambah").attr("id", idTombolTambah).addClass(idTombolTambah));
                }
                $("#" + idTombolTambah).on("click", $.proxy(function() {
                        if (this.options.idDialogEdit===undefined) {
                                this.tampilkanDialog();
                        } else {
                                if (this.options.callbackTambah!==undefined) {
                                        if ($.isFunction(this.options.callbackTambah)) {
                                                this.options.callbackTambah();
                                                $(this).data("dialogEksternalIndexBaris", -1);  // data indexBaris = -1 menunjukkan operasi tambah
                                        } else {
                                                throw new Error("Nilai callbackTambah harus berupa sebuah function!");
                                        }
                                }
                        }
                }, this));
        },

        _buatHiddenInput: function(indexBaris, indexKolom, kolom, parent) {
                $("<input type='hidden' />")
                        .attr("name", this.options.parameterKolom[indexKolom]+indexBaris)
                        .attr("id", this.options.parameterKolom[indexKolom]+indexBaris)
                        .val($(kolom).text())
                        .appendTo(parent);
        },

        _buatTombolEdit: function(indexBaris, parent) {
                var tombolEdit = $("<td><input type='button' value='Edit' class='jlEdit' data-baris='" + indexBaris + "'/></td>").appendTo(parent);
                tombolEdit.on("click", $.proxy(function() {
                        if (this.options.idDialogEdit===undefined) {
                                this.tampilkanDialog(indexBaris);
                        } else {
                                if (this.options.callbackEdit!==undefined) {
                                        if ($.isFunction(this.options.callbackEdit)) {
                                                var parameter = new Object();
                                                for (var i=0; i<this.options.parameterKolom.length; i++) {
                                                        var nilai = $("tbody tr:eq(" + indexBaris + ") td:eq(" + i + ")", this.element).text();
                                                        parameter[this.options.parameterKolom[i]] = nilai;
                                                }
                                                this.options.callbackEdit(parameter);
                                                $(this).data("dialogEksternalIndexBaris", indexBaris);
                                        } else {
                                                throw new Error("Nilai callbackEdit harus berupa sebuah function!");
                                        }
                                }
                        }
                }, this));
        },

        _buatTombolHapus: function(indexBaris, parent) {
                var tombolHapus = $("<td><input type='button' value='Hapus' class='jlHapus' data-baris='" + indexBaris + "'/></td>").appendTo(parent);
                tombolHapus.on("click", $.proxy(function() {

                        // Mengerjakan callbackHapus bila diperlukan
                        if (this.options.idDialogEdit!==undefined && this.options.callbackHapus!==undefined) {
                                if ($.isFunction(this.options.callbackHapus)) {
                                        var parameter = new Object();
                                        for (var i=0; i<this.options.parameterKolom.length; i++) {
                                                var nilai = $("tbody tr:eq(" + indexBaris + ") td:eq(" + i + ")", this.element).text();
                                                parameter[this.options.parameterKolom[i]] = nilai;
                                        }
                                        this.options.callbackHapus(parameter);
                                } else {
                                        throw new Error("Nilai callbackHapus harus berupa sebuah function!");
                                }
                        }

                        // menghapus baris tabel
                        this.element.find("tbody tr").eq(indexBaris).remove();

                        // menghapus <input type="hidden"/>
                        for (var i=0; i<this.options.parameterKolom.length; i++) {
                                $("input#" + this.options.parameterKolom[i]+indexBaris, this.element).remove();
                        }

                        // memperbaharui urutan <input type="hidden"/>
                        var jumlahBaris = this.element.find("tbody tr").length;
                        for (var i=indexBaris; i<jumlahBaris; i++) {
                                for (var j=0; j<this.options.parameterKolom.length; j++) {
                                        $("input#" + this.options.parameterKolom[j]+(i+1), this.element)
                                                .attr("id", this.options.parameterKolom[j]+i)
                                                .attr("name", this.options.parameterKolom[j]+i)
                                }
                        }

                        // memperbaharui binding untuk edit dan hapus
                        var baris = this.element.find("tbody tr");
                        for (var i=0; i<baris.length; i++) {
                                var curBaris = $(baris[i]);
                                curBaris.find("td input.jlEdit").parent().remove();
                                curBaris.find("td input.jlHapus").parent().remove();
                                this._buatTombolEdit(i, curBaris);
                                this._buatTombolHapus(i, curBaris);
                        }

                        // memperbaharui jumlah baris
                        this.element.find('input#jumlahBaris').val(jumlahBaris);
                }, this));
        },

        tampilkanDialog: function(indexBaris) {
            var isNew = false;
                if (indexBaris===undefined || indexBaris<0) {
                        isNew = true;
                }
                var dialogEdit = $("<div />").attr("id", this.element.attr("id")+"-dialog");
                var tabel = $("<table />");
                var baris = $("<tr />");
                var data = $("<td />");
                var inputText = $("<input type='text'/>");
                var inputCombo = $("<select />");

                // Menambahkan setiap kolom yang ada sebagai 'baris' di dialog
                for (var i=0; i<this.options.label.length; i++) {
                        var curBaris = baris.clone();
                        data.clone().text(this.options.label[i]).appendTo(curBaris);
                        var namaParameter = this.options.parameterKolom[i];

                        // Tambahkan nilai untuk setiap <input> bila ini adalah edit
                        var nilaiParameter = $("tbody tr:eq(" + indexBaris + ") td:eq(" + i + ")", this.element).text();
                        var input;
                        if (this.options.tipeKolom[i]==="string") {
                                input = inputText.clone().attr("name", namaParameter).attr("id", namaParameter).appendTo(curBaris);
                                if (!isNew) {
                                        input.val(nilaiParameter);
                                }
                        } else if (this.options.tipeKolom[i] instanceof Array) {
                                input = inputCombo.clone();
                                input.attr("name", namaParameter).attr("id", namaParameter);
                                for (var indexPilihan=0; indexPilihan<this.options.tipeKolom[i].length; indexPilihan++) {
                                        var nilai = this.options.tipeKolom[i][indexPilihan];
                                        $("<option/>").val(nilai).text(nilai).appendTo(input);
                                }
                                input.appendTo(curBaris);
                                if (!isNew) {
                                        input.val(nilaiParameter);
                                }
                        }

                        curBaris.appendTo(tabel);
                }

                // Menampilkan dialog
                var self = this;
                tabel.appendTo(dialogEdit);
                dialogEdit.dialog($.extend({
                        title: "Edit Data",
                        modal: true,
                        width: "auto",
                        height: "auto",
                        resizable: false,
                        buttons: {
                                "Ok": function() {
                                        if (isNew) {
                                                self.tambahBaris();
                                        } else {
                                                self.simpanPerubahan(indexBaris);
                                        }
                                        dialogEdit.dialog("destroy");
                                        dialogEdit.remove();
                                },
                                "Batal": function() {
                                        dialogEdit.dialog("destroy");
                                        dialogEdit.remove();
                                }
                        }
                }, this.options.dialogOptions));
        },

        simpanPerubahan: function(indexBaris) {
                var dialog = $("div#" + this.element.attr("id")+"-dialog");
                var baris = this.element.find("tbody tr").eq(indexBaris);
                for (var i=0; i<this.options.parameterKolom.length; i++) {
                        var nilai = $("#" + this.options.parameterKolom[i], dialog).val();
                        baris.find("td").eq(i).text(nilai);
                        $("input#" + this.options.parameterKolom[i]+indexBaris, this.element).val(nilai);
                }
        },

        _simpanPerubahanUntukEksternal: function(nilai) {
                // TODO: Perlu di-refactor untuk mengurangi duplikasi (dengan method simpanPerubahan)!
                var indexBaris = $(this).data("dialogEksternalIndexBaris");
                var baris = this.element.find("tbody tr").eq(indexBaris);
                for (var i=0; i<this.options.parameterKolom.length; i++) {
                        baris.find("td").eq(i).text(nilai[this.options.parameterKolom[i]]);
                        $("input#" + this.options.parameterKolom[i]+indexBaris, this.element).val(nilai[this.options.parameterKolom[i]]);
                }
        },

        tambahBaris: function() {
                var dialog = $("div#" + this.element.attr("id")+"-dialog");
                var baris = $("<tr />");
                var indexBaris = this.element.find("tbody tr").length;
                for (var i=0; i<this.options.parameterKolom.length; i++) {
                        var nilai = $("#" + this.options.parameterKolom[i], dialog).val();
                        var kolom = $("<td />").text(nilai).appendTo(baris);
                        this._buatHiddenInput(indexBaris, i, kolom, this.element);
                }
                this._buatTombolEdit(indexBaris, baris);
                this._buatTombolHapus(indexBaris, baris);
                this.element.find('input#jumlahBaris').val(indexBaris+1);
                baris.appendTo(this.element.find("tbody"));
        },

        _tambahBarisUntukEksternal: function(nilai) {
                // TODO: Perlu di-refactor untuk mengurangi duplikasi (dengan method tambahBaris)!
                var baris = $("<tr />");
                var indexBaris = this.element.find("tbody tr").length;
                for (var i=0; i<this.options.parameterKolom.length; i++) {
                        var kolom = $("<td />").text(nilai[this.options.parameterKolom[i]]).appendTo(baris);
                        this._buatHiddenInput(indexBaris, i, kolom, this.element);
                }
                this._buatTombolEdit(indexBaris, baris);
                this._buatTombolHapus(indexBaris, baris);
                this.element.find('input#jumlahBaris').val(indexBaris+1);
                baris.appendTo(this.element.find("tbody"));
        },

        prosesDialogEksternal: function(nilai) {
                var indexBaris = $(this).data("dialogEksternalIndexBaris");
                if (indexBaris===undefined || indexBaris<0) {
                        // operasi tambah dari dialog buatan user
                        this._tambahBarisUntukEksternal(nilai);
                } else {
                        // operasi edit dari dialog buatan user
                        this._simpanPerubahanUntukEksternal(nilai);
                }
        },

        _setOption: function(key, value) {
                // TODO: Mungkin fitur untuk mengubah option, misalnya mengubah definisi kolom, perlu ditambahkan suatu saat nanti?
                throw new Error("Option pada jlSimpleTableEditor tidak dapat dimodifikasi dan hanya bisa diberikan pada saat pertama kali dibuat.");
        },

        _destroy: function() {
                this.element.find(".jlEdit").parent().remove();
                this.element.find(".jlHapus").parent().remove();
                this.element.find("input[type='hidden']").remove();
        },

});

})(jQuery);

