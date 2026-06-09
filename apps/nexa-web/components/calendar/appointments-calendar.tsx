"use client";

import { useEffect, useMemo, useRef } from "react";
import { Calendar, type EventClickArg, type EventInput } from "@fullcalendar/core";
import frLocale from "@fullcalendar/core/locales/fr";
import arLocale from "@fullcalendar/core/locales/ar";
import dayGridPlugin from "@fullcalendar/daygrid";
import interactionPlugin from "@fullcalendar/interaction";
import timeGridPlugin from "@fullcalendar/timegrid";

import { useLocale } from "@/components/providers/locale-provider";
import type { AppointmentSummary } from "@/lib/api/rdvs";

import "./appointments-calendar.css";

const calendarPlugins = [dayGridPlugin, timeGridPlugin, interactionPlugin];

type AppointmentsCalendarProps = {
  appointments: AppointmentSummary[];
  readOnly?: boolean;
  onDateClick?: (date: Date) => void;
  onEventClick?: (appointment: AppointmentSummary) => void;
};

function toEvent(appointment: AppointmentSummary): EventInput | null {
  if (!appointment.start) {
    return null;
  }

  const customer = appointment.customer?.display_name;

  return {
    id: String(appointment.id),
    title: customer
      ? `${appointment.title} · ${customer}`
      : appointment.title,
    start: appointment.start,
    backgroundColor: appointment.color,
    borderColor: appointment.color,
    extendedProps: {
      appointment,
    },
  };
}

export function AppointmentsCalendar({
  appointments,
  readOnly = false,
  onDateClick,
  onEventClick,
}: AppointmentsCalendarProps) {
  const containerRef = useRef<HTMLDivElement>(null);
  const calendarRef = useRef<Calendar | null>(null);
  const onDateClickRef = useRef(onDateClick);
  const onEventClickRef = useRef(onEventClick);
  const { locale, t } = useLocale();

  onDateClickRef.current = onDateClick;
  onEventClickRef.current = onEventClick;

  const calendarLocale = useMemo(() => {
    if (locale === "fr") {
      return frLocale;
    }

    if (locale === "ar") {
      return arLocale;
    }

    return undefined;
  }, [locale]);

  const events = useMemo(
    () =>
      appointments
        .map(toEvent)
        .filter((event): event is EventInput => event !== null),
    [appointments],
  );

  useEffect(() => {
    const element = containerRef.current;

    if (!element) {
      return;
    }

    const calendar = new Calendar(element, {
      plugins: calendarPlugins,
      initialView: "dayGridMonth",
      locale: calendarLocale,
      headerToolbar: {
        left: "prev,next today",
        center: "title",
        right: "dayGridMonth,timeGridWeek,timeGridDay",
      },
      buttonText: {
        today: t("common.today"),
        month: t("common.month"),
        week: t("common.week"),
        day: t("common.day"),
      },
      height: "auto",
      events,
      editable: false,
      selectable: !readOnly,
      nowIndicator: true,
      slotMinTime: "08:00:00",
      slotMaxTime: "21:00:00",
      allDaySlot: false,
      dayMaxEvents: 3,
      eventDisplay: "block",
      dateClick: (info) => {
        if (!readOnly) {
          onDateClickRef.current?.(info.date);
        }
      },
      eventClick: (info: EventClickArg) => {
        const appointment = info.event.extendedProps.appointment as
          | AppointmentSummary
          | undefined;

        if (appointment) {
          onEventClickRef.current?.(appointment);
        }
      },
    });

    calendar.render();
    calendarRef.current = calendar;

    return () => {
      calendar.destroy();
      calendarRef.current = null;
    };
  }, [calendarLocale, readOnly]);

  useEffect(() => {
    const calendar = calendarRef.current;

    if (!calendar) {
      return;
    }

    calendar.removeAllEvents();
    events.forEach((event) => calendar.addEvent(event));
  }, [events]);

  useEffect(() => {
    const calendar = calendarRef.current;

    if (!calendar) {
      return;
    }

    calendar.setOption("selectable", !readOnly);
    calendar.setOption("locale", calendarLocale);
    calendar.setOption("buttonText", {
      today: t("common.today"),
      month: t("common.month"),
      week: t("common.week"),
      day: t("common.day"),
    });
  }, [readOnly, calendarLocale, t]);

  return (
    <div className="appointments-calendar rounded-xl border bg-card p-2 md:p-4">
      <div ref={containerRef} />
    </div>
  );
}
