/**
 * App Calendar
 */

/**
 * ! If both start and end dates are same Full calendar will nullify the end date value.
 * ! Full calendar will end the event on a day before at 12:00:00AM thus, event won't extend to the end date.
 * ! We are getting events from a separate file named app-calendar-events.js. You can add or remove events from there.
 *
 **/

"use strict";

let direction = "ltr";

if (isRtl) {
    direction = "rtl";
}

document.addEventListener("DOMContentLoaded", function () {
    (function () {
        const calendarEl = document.getElementById("calendar"),
            appCalendarSidebar = document.querySelector(
                ".app-calendar-sidebar",
            ),
            addEventSidebar = document.getElementById("addEventSidebar"),
            appOverlay = document.querySelector(".app-overlay"),
            calendarsColor = {
                Business: "primary",
                Holiday: "success",
                Personal: "danger",
                Family: "warning",
                ETC: "info",
            },
            offcanvasTitle = document.querySelector(".offcanvas-title"),
            btnToggleSidebar = document.querySelector(".btn-toggle-sidebar"),
            btnSubmit = document.querySelector("#addEventBtn"),
            btnDeleteEvent = document.querySelector(".btn-delete-event"),
            btnCancel = document.querySelector(".btn-cancel"),
            eventTitle = document.querySelector("#eventTitle"),
            eventStartDate = document.querySelector("#eventStartDate"),
            eventEndDate = document.querySelector("#eventEndDate"),
            eventUrl = $("#eventURL"), // ! Using jquery vars due to select2 jQuery dependency
            eventLabel = $("#eventLabel"), // ! Using jquery vars due to select2 jQuery dependency
            eventGuests = $("#eventGuests"), // ! Using jquery vars due to select2 jQuery dependency
            eventLocation = document.querySelector("#eventLocation"),
            eventDescription = document.querySelector("#eventDescription"),
            allDaySwitch = document.querySelector(".allDay-switch"),
            selectAll = document.querySelector(".select-all"),
            filterInput = [].slice.call(
                document.querySelectorAll(".input-filter"),
            ),
            inlineCalendar = document.querySelector(".inline-calendar");

        let eventToUpdate,
            currentEvents = events, // Assign app-calendar-events.js file events (assume events from API) to currentEvents (browser store/object) to manage and update calender events
            isFormValid = false,
            inlineCalInstance;

        // Init event Offcanvas
        const bsAddEventSidebar = new bootstrap.Offcanvas(addEventSidebar);

        //! TODO: Update Event label and guest code to JS once select removes jQuery dependency
        // Event Label (select2)
        if (eventLabel.length) {
            function renderBadges(option) {
                if (!option.id) {
                    return option.text;
                }
                var $badge =
                    "<span class='badge badge-dot bg-" +
                    $(option.element).data("label") +
                    " me-2'> " +
                    "</span>" +
                    option.text;

                return $badge;
            }
            eventLabel.wrap('<div class="position-relative"></div>').select2({
                placeholder: "Selectionner une option",
                dropdownParent: eventLabel.parent(),
                templateResult: renderBadges,
                templateSelection: renderBadges,
                minimumResultsForSearch: -1,
                escapeMarkup: function (es) {
                    return es;
                },
            });
        }

        //Event Url
        if (eventUrl.length) {
            function renderBadges(option) {
                if (!option.id) {
                    return option.text;
                }
                var $badge =
                    "<span class='badge badge-dot bg-" +
                    $(option.element).data("label") +
                    " me-2'> " +
                    "</span>" +
                    option.text;

                return $badge;
            }
            eventUrl
                .wrap('<div class="position-relative"></div>')
                .select2({
                    placeholder: "Selectionner une option",
                    dropdownParent: eventUrl.parent(),
                    closeOnSelect: true,
                    templateResult: renderBadges,
                    templateSelection: renderBadges,
                    minimumResultsForSearch: -1,
                    escapeMarkup: function (es) {
                        return es;
                    },
                })
                .on("change", function () {
                    // Revalidate the color field when an option is chosen
                    fv.revalidateField("eventURL");
                });
        }

        // Event Guests (select2)
        if (eventGuests.length) {
            function renderGuestAvata(option) {
                if (!option.id) {
                    return option.text;
                }
                var $content =
                    "<div class='d-flex flex-wrap align-items-center'>" +
                    "<span class='me-2'>" +
                    option.text +
                    "</span>" +
                    "</div>";

                return $content;
            }
            eventGuests
                .wrap('<div class="position-relative"></div>')
                .select2({
                    placeholder: "Selectionner une option",
                    dropdownParent: eventGuests.parent(),
                    closeOnSelect: true,
                    templateResult: renderGuestAvata,
                    templateSelection: renderGuestAvata,
                    escapeMarkup: function (es) {
                        return es;
                    },
                })
                .on("change", function () {
                    // Revalidate the color field when an option is chosen
                    fv.revalidateField("eventGuests");
                });
        }

        // Event start (flatpicker)
        if (eventStartDate) {
            var start = eventStartDate.flatpickr({
                enableTime: true,
                altFormat: "Y-m-dTH:i:S",
                onReady: function (selectedDates, dateStr, instance) {
                    if (instance.isMobile) {
                        instance.mobileInput.setAttribute("step", null);
                    }
                },
            });
        }

        // Event end (flatpicker)
        if (eventEndDate) {
            var end = eventEndDate.flatpickr({
                enableTime: true,
                altFormat: "Y-m-dTH:i:S",
                onReady: function (selectedDates, dateStr, instance) {
                    if (instance.isMobile) {
                        instance.mobileInput.setAttribute("step", null);
                    }
                },
            });
        }

        // Inline sidebar calendar (flatpicker)
        if (inlineCalendar) {
            inlineCalInstance = inlineCalendar.flatpickr({
                monthSelectorType: "static",
                inline: true,
            });
        }

        // Event click function
        function eventClick(info) {
            eventToUpdate = info.event;

            if (eventToUpdate.url) {
                info.jsEvent.preventDefault();
            }
            bsAddEventSidebar.show();
            // For update event set offcanvas title text: Update Event
            if (offcanvasTitle) {
                offcanvasTitle.innerHTML = "Modification évènement";
            }
            btnSubmit.innerHTML = "Modifier";
            btnSubmit.classList.add("btn-update-event");
            btnSubmit.classList.remove("btn-add-event");
            btnDeleteEvent.classList.remove("d-none");

            eventTitle.value = eventToUpdate.title;
            eventUrl.val(eventToUpdate.url).trigger("change");
            start.setDate(eventToUpdate.start, true, "Y-m-d");
            // eventToUpdate.allDay === true
            //     ? (allDaySwitch.checked = true)
            //     : (allDaySwitch.checked = false);
            eventToUpdate.end !== null
                ? end.setDate(eventToUpdate.end, true, "Y-m-d")
                : end.setDate(eventToUpdate.start, true, "Y-m-d");
            eventLabel
                .val(eventToUpdate.extendedProps.calendar)
                .trigger("change");

            // eventToUpdate.extendedProps.location !== undefined
            //     ? (eventLocation.value = eventToUpdate.extendedProps.location)
            //     : null;
            eventToUpdate.extendedProps.guests !== undefined
                ? eventGuests
                      .val(eventToUpdate.extendedProps.guests)
                      .trigger("change")
                : null;
            // eventToUpdate.extendedProps.description !== undefined
            //     ? (eventDescription.value =
            //           eventToUpdate.extendedProps.description)
            //     : null;

            // // Call removeEvent function
            btnDeleteEvent.addEventListener("click", (e) => {
                const id = eventToUpdate.id;

                removeEvent(id);
                // eventToUpdate.remove();
                bsAddEventSidebar.hide();
            });
        }

        // Modify sidebar toggler
        function modifyToggler() {
            const fcSidebarToggleButton = document.querySelector(
                ".fc-sidebarToggle-button",
            );
            fcSidebarToggleButton.classList.remove("fc-button-primary");
            fcSidebarToggleButton.classList.add(
                "d-lg-none",
                "d-inline-block",
                "ps-0",
            );
            while (fcSidebarToggleButton.firstChild) {
                fcSidebarToggleButton.firstChild.remove();
            }
            fcSidebarToggleButton.setAttribute("data-bs-toggle", "sidebar");
            fcSidebarToggleButton.setAttribute("data-overlay", "");
            fcSidebarToggleButton.setAttribute(
                "data-target",
                "#app-calendar-sidebar",
            );
            fcSidebarToggleButton.insertAdjacentHTML(
                "beforeend",
                '<i class="ti ti-menu-2 ti-lg text-heading"></i>',
            );
        }

        // Filter events by calender
        function selectedCalendars() {
            let selected = [],
                filterInputChecked = [].slice.call(
                    document.querySelectorAll(".input-filter:checked"),
                );

            filterInputChecked.forEach((item) => {
                selected.push(item.getAttribute("data-value"));
            });

            return selected;
        }

        // --------------------------------------------------------------------------------------------------
        // AXIOS: fetchEvents
        // * This will be called by fullCalendar to fetch events. Also this can be used to refetch events.
        // --------------------------------------------------------------------------------------------------
        function fetchEvents(info, successCallback) {
            // Obtenir les calendriers sélectionnés
            const calendars = selectedCalendars();

            // Appel API avec fetch pour récupérer les événements
            fetch("/events", {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                },
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(
                            `Erreur HTTP! Statut : ${response.status}`,
                        );
                    }
                    return response.json();
                })
                .then((data) => {
                    // Filtrer les événements en fonction des calendriers sélectionnés
                    const filteredEvents = data.filter((event) =>
                        calendars.includes(
                            event.extendedProps.calendar.toLowerCase(),
                        ),
                    );

                    // Appeler successCallback pour transmettre les événements à FullCalendar
                    successCallback(filteredEvents);
                })
                .catch((error) => {
                    console.error(
                        "Erreur lors du chargement des événements :",
                        error,
                    );
                });
        }

        // Init FullCalendar
        // ------------------------------------------------
        let calendar = new Calendar(calendarEl, {
            locale: "fr",
            initialView: "dayGridMonth",
            allDaySlot: false,
            events: fetchEvents,
            plugins: [
                dayGridPlugin,
                interactionPlugin,
                listPlugin,
                timegridPlugin,
            ],
            editable: true,
            dragScroll: true,
            dayMaxEvents: 2,
            eventResizableFromStart: true,
            customButtons: {
                sidebarToggle: {
                    text: "Sidebar",
                },
            },
            headerToolbar: {
                start: "sidebarToggle, prev,next, title",
                end: "dayGridMonth,timeGridWeek,timeGridDay,listMonth",
            },
            buttonText: {
                today: "aujourd'hui",
                month: "Mois",
                list: "Liste",
                week: "Semaine",
                day: "Jour",
                prev: "Précédent",
                next: "Suivant",
            },
            direction: direction,
            initialDate: new Date(),
            navLinks: true, // can click day/week names to navigate views
            eventClassNames: function ({ event: calendarEvent }) {
                const colorName =
                    calendarsColor[calendarEvent._def.extendedProps.calendar];
                // Background Color
                return ["fc-event-" + colorName];
            },
            dateClick: function (info) {
                let date = moment(info.date).format("YYYY-MM-DD");
                resetValues();
                bsAddEventSidebar.show();

                // For new event set offcanvas title text: Add Event
                if (offcanvasTitle) {
                    offcanvasTitle.innerHTML = "Ajouter un évènement";
                }
                btnSubmit.innerHTML = "Ajouter";
                btnSubmit.classList.remove("btn-update-event");
                btnSubmit.classList.add("btn-add-event");
                btnDeleteEvent.classList.add("d-none");
                eventStartDate.value = date;
                eventEndDate.value = date;
            },
            eventClick: function (info) {
                eventClick(info);
            },
            datesSet: function () {
                modifyToggler();
            },

            viewDidMount: function () {
                modifyToggler();
            },
        });

        // Render calendar
        calendar.render();
        // Modify sidebar toggler
        modifyToggler();

        const eventForm = document.getElementById("eventForm");
        const fv = FormValidation.formValidation(eventForm, {
            fields: {
                // eventTitle: {
                //     validators: {
                //         notEmpty: {
                //             message: "Entrez un titre",
                //         },
                //     },
                // },
                eventStartDate: {
                    validators: {
                        notEmpty: {
                            message: "Entrez la date de début ",
                        },
                    },
                },
                eventEndDate: {
                    validators: {
                        notEmpty: {
                            message: "Entrez la date de fin ",
                        },
                    },
                },
                // eventURL: {
                //     validators: {
                //         notEmpty: {
                //             message: "Choisissez une classe",
                //         },
                //     },
                // },
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    // Use this for enabling/changing valid/invalid class
                    eleValidClass: "",
                    rowSelector: function (field, ele) {
                        // field is the field name & ele is the field element
                        return ".mb-5";
                    },
                }),
                submitButton: new FormValidation.plugins.SubmitButton(),
                // Submit the form when all fields are valid
                // defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                autoFocus: new FormValidation.plugins.AutoFocus(),
            },
        })
            .on("core.form.valid", function () {
                // Jump to the next step when all fields in the current step are valid
                isFormValid = true;
            })
            .on("core.form.invalid", function () {
                // if fields are invalid
                isFormValid = false;
            });

        // Sidebar Toggle Btn
        if (btnToggleSidebar) {
            btnToggleSidebar.addEventListener("click", (e) => {
                btnCancel.classList.remove("d-none");
            });
        }

        // Add Event
        // ------------------------------------------------
        function addEvent(eventData) {
            const startDate = new Date(eventData.start);
            const endDate = new Date(eventData.end);

            const payload = {
                title: eventData.title,
                start_date: startDate.toISOString().split("T")[0],
                end_date: endDate.toISOString().split("T")[0],
                heure_debut: `${String(startDate.getHours()).padStart(2, "0")}:${String(startDate.getMinutes()).padStart(2, "0")}`,
                heure_fin: `${String(endDate.getHours()).padStart(2, "0")}:${String(endDate.getMinutes()).padStart(2, "0")}`,
                module: eventData.extendedProps.guests,
                type: eventData.extendedProps.calendar,
                classe: eventData.url,
            };
            console.log(payload);

            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");

            fetch("/events", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify(payload),
            })
                .then((response) => {
                    if (response.status === 422) {
                        return response.json().then((data) => {
                            handleValidationErrors(data.errors);
                            console.error("Validation échouée :", data.errors);
                            alert(
                                "Erreur : Veuillez vérifier les champs saisis !",
                            );
                            throw new Error("Validation échouée");
                        });
                    } else if (response.status === 409) {
                        return response.json().then((data) => {
                            console.warn("Conflit détecté :", data.errors);
                            alert(
                                "⚠️ Un événement existe déjà sur cette période !",
                            );
                            throw new Error("Conflit d'événement");
                        });
                    } else if (!response.ok) {
                        throw new Error(`Erreur HTTP : ${response.status}`);
                    }
                    return response.json();
                })
                .then((data) => {
                    console.log("✅ Événement enregistré avec succès :", data);
                    alert("🎉 Événement ajouté avec succès !");
                    calendar.refetchEvents();
                })
                .catch((error) => {
                    console.error("Erreur lors de l'enregistrement :", error);
                });
        }

        function handleValidationErrors(errors) {
            // Réinitialiser les erreurs
            const fields = [
                "eventTitle",
                "eventStartDate",
                "eventEndDate",
                "eventGuests",
                "eventURL",
            ];
            fields.forEach((field) => {
                const input = document.getElementById(field);
                const errorContainer = document.getElementById(`${field}Error`);
                if (input) {
                    input.classList.remove("is-invalid");
                }
                if (errorContainer) {
                    errorContainer.innerHTML = "";
                }
            });

            // Afficher les erreurs spécifiques
            if (errors.title) {
                const errorContainer =
                    document.getElementById("eventTitleError");
                if (errorContainer) {
                    errorContainer.innerHTML = errors.title.join(", ");
                }
                const input = document.getElementById("eventTitle");
                if (input) {
                    input.classList.add("is-invalid");
                }
            }

            if (errors.start_date) {
                const errorContainer = document.getElementById(
                    "eventStartDateError",
                );
                if (errorContainer) {
                    errorContainer.innerHTML = errors.start_date.join(", ");
                }
                const input = document.getElementById("eventStartDate");
                if (input) {
                    input.classList.add("is-invalid");
                }
            }

            if (errors.end_date) {
                const errorContainer =
                    document.getElementById("eventEndDateError");
                if (errorContainer) {
                    errorContainer.innerHTML = errors.end_date.join(", ");
                }
                const input = document.getElementById("eventEndDate");
                if (input) {
                    input.classList.add("is-invalid");
                }
            }

            if (errors.heure_debut) {
                const errorContainer = document.getElementById(
                    "eventStartDateError",
                );
                if (errorContainer) {
                    errorContainer.innerHTML += `<br>${errors.heure_debut.join(", ")}`;
                }
                const input = document.getElementById("eventStartDate");
                if (input) {
                    input.classList.add("is-invalid");
                }
            }

            if (errors.heure_fin) {
                const errorContainer =
                    document.getElementById("eventEndDateError");
                if (errorContainer) {
                    errorContainer.innerHTML += `<br>${errors.heure_fin.join(", ")}`;
                }
                const input = document.getElementById("eventEndDate");
                if (input) {
                    input.classList.add("is-invalid");
                }
            }

            if (errors.module) {
                const errorContainer =
                    document.getElementById("eventGuestsError");
                if (errorContainer) {
                    errorContainer.innerHTML = errors.module.join(", ");
                }
                const input = document.getElementById("eventGuests");
                if (input) {
                    input.classList.add("is-invalid");
                }
            }

            if (errors.classe) {
                const errorContainer = document.getElementById("eventURLError");
                if (errorContainer) {
                    errorContainer.innerHTML = errors.classe.join(", ");
                }
                const input = document.getElementById("eventURL");
                if (input) {
                    input.classList.add("is-invalid");
                }
            }
        }

        // Update Event
        // ------------------------------------------------
        function updateEvent(eventData) {
            eventData.id = eventData.id;
            const startDate = new Date(eventData.start);
            const endDate = new Date(eventData.end);

            // Préparer les données à envoyer
            const payload = {
                title: eventData.title,
                start_date: startDate.toISOString().split("T")[0],
                end_date: endDate.toISOString().split("T")[0],
                heure_debut: `${String(startDate.getHours()).padStart(2, "0")}:${String(startDate.getMinutes()).padStart(2, "0")}`,
                heure_fin: `${String(endDate.getHours()).padStart(2, "0")}:${String(endDate.getMinutes()).padStart(2, "0")}`,
                module: eventData.extendedProps.guests,
                type: eventData.extendedProps.calendar,
                calendar: eventData.extendedProps.calendar,
                classe: eventData.url,
            };

            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");

            // Requête pour mettre à jour l'événement
            fetch(`/events/${eventData.id}`, {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify(payload),
            })
                .then((response) => {
                    if (response.status === 422) {
                        return response.json().then((data) => {
                            handleValidationErrors(data.errors);
                            alert(
                                "Erreur de validation : Veuillez vérifier les champs.",
                            );
                            throw new Error("Validation échouée");
                        });
                    } else if (!response.ok) {
                        alert(
                            `Erreur lors de la mise à jour de l'événement : ${response.status}`,
                        );
                        throw new Error(`Erreur HTTP : ${response.status}`);
                    }
                    return response.json();
                })
                .then((data) => {
                    console.log("Événement mis à jour avec succès :", data);
                    alert("Événement mis à jour avec succès !");

                    // Mettre à jour l'événement dans l'objet currentEvents
                    currentEvents[
                        currentEvents.findIndex(
                            (el) => el.id === parseInt(eventData.id),
                        )
                    ] = {
                        ...eventData,
                        start: startDate,
                        end: endDate,
                    };

                    // Rafraîchir les événements sur le calendrier
                    calendar.refetchEvents();
                })
                .catch((error) => {
                    console.error(
                        "Erreur lors de la mise à jour de l'événement :",
                        error,
                    );
                    alert("Une erreur est survenue lors de la mise à jour.");
                });
        }

        // Remove Event
        // ------------------------------------------------

        function removeEvent(eventId) {
            const id = eventId;

            // Requête fetch pour supprimer l'événement
            fetch(`/events/${id}`, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"), // Ajout du token CSRF si nécessaire
                },
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error(
                            "Erreur lors de la suppression de l'événement.",
                        );
                    }
                    return response.json();
                })
                .then((data) => {
                    console.log("Événement supprimé avec succès :", data);

                    // Supprimer l'événement de l'objet currentEvents
                    currentEvents = currentEvents.filter(function (event) {
                        return event.id != eventId;
                    });

                    // Rafraîchir les événements sur le calendrier
                    calendar.refetchEvents();
                })
                .catch((error) => {
                    console.error("Erreur :", error);
                });
        }

        // (Update Event In Calendar (UI Only)
        // ------------------------------------------------
        const updateEventInCalendar = (
            updatedEventData,
            propsToUpdate,
            extendedPropsToUpdate,
        ) => {
            const existingEvent = calendar.getEventById(updatedEventData.id);

            // --- Set event properties except date related ----- //
            // ? Docs: https://fullcalendar.io/docs/Event-setProp
            // dateRelatedProps => ['start', 'end', 'allDay']
            // eslint-disable-next-line no-plusplus
            for (var index = 0; index < propsToUpdate.length; index++) {
                var propName = propsToUpdate[index];
                existingEvent.setProp(propName, updatedEventData[propName]);
            }

            // --- Set date related props ----- //
            // ? Docs: https://fullcalendar.io/docs/Event-setDates
            existingEvent.setDates(
                updatedEventData.start,
                updatedEventData.end,
                {
                    allDay: updatedEventData.allDay,
                },
            );

            // --- Set event's extendedProps ----- //
            // ? Docs: https://fullcalendar.io/docs/Event-setExtendedProp
            // eslint-disable-next-line no-plusplus
            for (var index = 0; index < extendedPropsToUpdate.length; index++) {
                var propName = extendedPropsToUpdate[index];
                existingEvent.setExtendedProp(
                    propName,
                    updatedEventData.extendedProps[propName],
                );
            }
        };

        // Remove Event In Calendar (UI Only)
        // ------------------------------------------------
        function removeEventInCalendar(eventId) {
            calendar.getEventById(eventId).remove();
        }

        // Add new event
        // ------------------------------------------------
        btnSubmit.addEventListener("click", (e) => {
            if (btnSubmit.classList.contains("btn-add-event")) {
                if (isFormValid) {
                    let newEvent = {
                        id: calendar.getEvents().length + 1,
                        title: eventTitle.value,
                        start: eventStartDate.value,
                        end: eventEndDate.value,
                        startStr: eventStartDate.value,
                        endStr: eventEndDate.value,
                        display: "block",
                        extendedProps: {
                            // location: eventLocation.value,
                            guests: eventGuests.val(),
                            calendar: eventLabel.val(),
                            // description: eventDescription.value,
                        },
                    };
                    // if (eventUrl.value) {
                    // newEvent.url = eventUrl.value;
                    newEvent.url = eventUrl.val();

                    // }
                    // if (allDaySwitch.checked) {
                    //     newEvent.allDay = true;
                    // }
                    addEvent(newEvent);
                    bsAddEventSidebar.hide();
                }
            } else {
                // Update event
                // ------------------------------------------------
                if (isFormValid) {
                    let eventData = {
                        id: eventToUpdate.id,
                        title: eventTitle.value,
                        start: eventStartDate.value,
                        end: eventEndDate.value,
                        url: eventUrl.val(),
                        extendedProps: {
                            // location: eventLocation.value,
                            guests: eventGuests.val(),
                            calendar: eventLabel.val(),
                            // description: eventDescription.value,
                        },
                        display: "block",
                        // allDay: allDaySwitch.checked ? true : false,
                    };

                    updateEvent(eventData);
                    bsAddEventSidebar.hide();
                }
            }
        });

        // Call removeEvent function
        btnDeleteEvent.addEventListener("click", (e) => {
            removeEvent(eventToUpdate.id);
            // eventToUpdate.remove();
            bsAddEventSidebar.hide();
        });

        // Reset event form inputs values
        // ------------------------------------------------
        function resetValues() {
            eventEndDate.value = "";
            eventUrl.val("").trigger("change");
            eventStartDate.value = "";
            eventTitle.value = "";
            // eventLocation.value = "";
            // allDaySwitch.checked = false;
            eventGuests.val("").trigger("change");
            // eventDescription.value = "";
        }

        // When modal hides reset input values
        addEventSidebar.addEventListener("hidden.bs.offcanvas", function () {
            resetValues();
        });

        // Hide left sidebar if the right sidebar is open
        btnToggleSidebar.addEventListener("click", (e) => {
            if (offcanvasTitle) {
                offcanvasTitle.innerHTML = "Ajouter évènement";
            }
            btnSubmit.innerHTML = "Ajouter";
            btnSubmit.classList.remove("btn-update-event");
            btnSubmit.classList.add("btn-add-event");
            btnDeleteEvent.classList.add("d-none");
            appCalendarSidebar.classList.remove("show");
            appOverlay.classList.remove("show");
        });

        // Calender filter functionality
        // ------------------------------------------------
        if (selectAll) {
            selectAll.addEventListener("click", (e) => {
                if (e.currentTarget.checked) {
                    document
                        .querySelectorAll(".input-filter")
                        .forEach((c) => (c.checked = 1));
                } else {
                    document
                        .querySelectorAll(".input-filter")
                        .forEach((c) => (c.checked = 0));
                }
                calendar.refetchEvents();
            });
        }

        if (filterInput) {
            filterInput.forEach((item) => {
                item.addEventListener("click", () => {
                    document.querySelectorAll(".input-filter:checked").length <
                    document.querySelectorAll(".input-filter").length
                        ? (selectAll.checked = false)
                        : (selectAll.checked = true);
                    calendar.refetchEvents();
                });
            });
        }

        // Jump to date on sidebar(inline) calendar change
        inlineCalInstance.config.onChange.push(function (date) {
            calendar.changeView(
                calendar.view.type,
                moment(date[0]).format("YYYY-MM-DD"),
            );
            modifyToggler();
            appCalendarSidebar.classList.remove("show");
            appOverlay.classList.remove("show");
        });
    })();
});
