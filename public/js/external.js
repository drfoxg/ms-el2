const loadWarehouses = (message) => {
    fetch("https://sulphur.fun/api/warehouse")
        .then((responce) => responce.json())
        .then((dataIn) => {
            /*
            { title: '#' },
            { title: 'Парт номер' },
            { title: 'Производитель' },
            { title: 'Поставщик' },
            { title: 'Количество' },
            { title: 'Комментарий' }
            */
            //$("<tfoot/>").append($("#display_json_data thead tr").clone());

            let mainTable = new DataTable("#display_json_data", {
                scrollX: "100%",
                footer: true,
                /*
                footer: [
                    "#",
                    "Парт номер",
                    "Производитель",
                    "Количество",
                    "Комментарий",
                ],
                */

                initComplete: function () {
                    this.api()
                        .columns()
                        .every(function () {
                            let column = this;
                            let title = column.header().textContent;

                            // Create input element
                            let input = document.createElement("input");
                            let br = document.createElement("br");
                            input.placeholder = "Что ищете?"//title;
                            column.header().appendChild(br);
                            column.header().appendChild(input);
                            //let br = "<br>";
                            //column.header().innerHTML('&amp;lt;input type=&amp;quot;text&amp;quot; placeholder=&amp;quot;Search '+title+'&amp;quot; /&amp;gt;');

                            // Event listener for user input
                            input.addEventListener("keyup", () => {
                                if (column.search() !== this.value) {
                                    column.search(input.value).draw();
                                }
                            });

                            input.addEventListener('click', (e) => {
                                e.stopPropagation(); // Prevents event propagation to parent elements
                            });

                        });
                },

                data: dataIn.data,
                columns: [
                    { title: "#", data: "id" },
                    { title: "Парт номер", data: "part_number" },
                    { title: "Производитель", data: "manufacturer_name" },
                    { title: "Количество", data: "stock_quantity" },
                    { title: "Комментарий", data: "comment" },
                ],
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.7/i18n/ru.json",
                },
                /*
                fnFooterCallback: function (row, data, start, end, display) {
                    var api = this.api();
                    var footer = $(this).append("<tfoot><tr></tr></tfoot>");
                    this.api()
                        .columns()
                        .every(function () {
                            var column = this;
                            $(footer).append(
                                '<th><input type="text" style="width:100%;"></th>'
                            );
                        });
                },
                */
            });

            //$(mainTable.table()).removeClass("no-footer");

            console.log(dataIn);
            //convertJsonToBootstrapTable(data);
        });


    //$("<tfoot/>").append($("#display_json_data thead tr").clone());
    //$('#display_json_data tfoot tr').appendTo('#display_json_data thead');
};

//loadWarehouses();
