"use client";

import { useMemo, useState } from "react";
import { format, startOfDay } from "date-fns";
import { CalendarIcon, ChevronDownIcon, Clock3Icon } from "lucide-react";

import { useLocale } from "@/components/providers/locale-provider";
import { Button } from "@/components/ui/button";
import { Calendar } from "@/components/ui/calendar";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from "@/components/ui/popover";
import { combineDateAndTime } from "@/lib/calendar/datetime";
import { getDateFnsLocale } from "@/lib/calendar/date-fns-locale";
import { cn } from "@/lib/utils";

type DateTimePickerFieldProps = {
  date?: Date;
  time: string;
  onDateChange: (date: Date | undefined) => void;
  onTimeChange: (time: string) => void;
  dateLabel: string;
  timeLabel: string;
  selectDateLabel: string;
  previewLabel?: string;
  dateId?: string;
  timeId?: string;
  disabled?: boolean;
  layout?: "compact" | "inline";
};

export function DateTimePickerField({
  date,
  time,
  onDateChange,
  onTimeChange,
  dateLabel,
  timeLabel,
  selectDateLabel,
  previewLabel,
  dateId,
  timeId,
  disabled = false,
  layout = "compact",
}: DateTimePickerFieldProps) {
  const { locale, formatDateTime } = useLocale();
  const [open, setOpen] = useState(false);
  const dayPickerLocale = useMemo(() => getDateFnsLocale(locale), [locale]);

  const preview = useMemo(() => {
    if (!date || !time.trim()) {
      return null;
    }

    const combined = combineDateAndTime(date, time);

    if (!combined) {
      return null;
    }

    return formatDateTime(combined.toISOString());
  }, [date, formatDateTime, time]);

  if (layout === "inline") {
    return (
      <div className="space-y-4">
        <div className="rounded-xl border bg-background p-3">
          <Calendar
            mode="single"
            selected={date}
            captionLayout="dropdown"
            locale={dayPickerLocale}
            disabled={{ before: startOfDay(new Date()) }}
            className="mx-auto w-full max-w-sm"
            onSelect={onDateChange}
          />
        </div>

        <div className="space-y-2">
          <Label htmlFor={timeId} className="text-muted-foreground">
            {timeLabel}
          </Label>
          <div className="relative">
            <Clock3Icon className="pointer-events-none absolute start-2.5 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
            <Input
              id={timeId}
              type="time"
              step={60}
              value={time}
              disabled={disabled || !date}
              onChange={(event) => onTimeChange(event.target.value)}
              className="h-10 bg-background ps-8 appearance-none [&::-webkit-calendar-picker-indicator]:hidden [&::-webkit-calendar-picker-indicator]:appearance-none"
            />
          </div>
        </div>

        {preview ? (
          <p className="rounded-lg border border-dashed bg-primary/5 px-3 py-2 text-sm">
            {previewLabel ? (
              <span className="font-medium text-foreground">
                {previewLabel}:{" "}
              </span>
            ) : null}
            <span className="text-muted-foreground">{preview}</span>
          </p>
        ) : null}
      </div>
    );
  }

  return (
    <div className="space-y-3">
      <div className="grid gap-3 sm:grid-cols-2">
        <div className="space-y-2">
          <Label htmlFor={dateId} className="text-muted-foreground">
            {dateLabel}
          </Label>
          <Popover open={open} onOpenChange={setOpen}>
            <PopoverTrigger
              disabled={disabled}
              render={
                <Button
                  type="button"
                  variant="outline"
                  id={dateId}
                  disabled={disabled}
                  className={cn(
                    "h-9 w-full justify-between px-2.5 font-normal",
                    !date && "text-muted-foreground",
                  )}
                />
              }
            >
              <span className="inline-flex items-center gap-2 truncate">
                <CalendarIcon className="size-4 shrink-0 opacity-70" />
                {date
                  ? format(date, "PPP", { locale: dayPickerLocale })
                  : selectDateLabel}
              </span>
              <ChevronDownIcon className="size-4 shrink-0 opacity-50" />
            </PopoverTrigger>
            <PopoverContent
              className="w-auto overflow-hidden p-0"
              align="start"
              sideOffset={6}
            >
              <Calendar
                mode="single"
                selected={date}
                captionLayout="dropdown"
                locale={dayPickerLocale}
                disabled={{ before: startOfDay(new Date()) }}
                onSelect={(nextDate) => {
                  onDateChange(nextDate);
                  setOpen(false);
                }}
              />
            </PopoverContent>
          </Popover>
        </div>

        <div className="space-y-2">
          <Label htmlFor={timeId} className="text-muted-foreground">
            {timeLabel}
          </Label>
          <div className="relative">
            <Clock3Icon className="pointer-events-none absolute start-2.5 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" />
            <Input
              id={timeId}
              type="time"
              step={60}
              value={time}
              disabled={disabled || !date}
              onChange={(event) => onTimeChange(event.target.value)}
              className="h-9 bg-background ps-8 appearance-none [&::-webkit-calendar-picker-indicator]:hidden [&::-webkit-calendar-picker-indicator]:appearance-none"
            />
          </div>
        </div>
      </div>

      {preview ? (
        <p className="rounded-lg border border-dashed bg-muted/30 px-3 py-2 text-sm text-muted-foreground">
          {previewLabel ? (
            <>
              <span className="font-medium text-foreground">{previewLabel}</span>
              {" · "}
            </>
          ) : null}
          {preview}
        </p>
      ) : null}
    </div>
  );
}
