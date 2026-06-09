export function parseAppointmentDateTime(value: string | null | undefined): {
  date: Date | undefined;
  time: string;
} {
  if (!value) {
    return { date: undefined, time: "" };
  }

  const parsed = new Date(value);

  if (Number.isNaN(parsed.getTime())) {
    return { date: undefined, time: "" };
  }

  const pad = (part: number) => String(part).padStart(2, "0");

  return {
    date: parsed,
    time: `${pad(parsed.getHours())}:${pad(parsed.getMinutes())}`,
  };
}

export function combineDateAndTime(date: Date, time: string): Date | null {
  const [hours, minutes] = time.split(":").map(Number);

  if (
    Number.isNaN(hours) ||
    Number.isNaN(minutes) ||
    hours < 0 ||
    hours > 23 ||
    minutes < 0 ||
    minutes > 59
  ) {
    return null;
  }

  const combined = new Date(date);
  combined.setHours(hours, minutes, 0, 0);
  return combined;
}

export function toApiDatetime(date: Date, time: string): string | null {
  const combined = combineDateAndTime(date, time);

  if (!combined) {
    return null;
  }

  return combined.toISOString().slice(0, 19).replace("T", " ");
}

export function isAppointmentDateTimeComplete(
  date: Date | undefined,
  time: string,
): date is Date {
  return Boolean(date && time.trim());
}
