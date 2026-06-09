"use client";

import type { CalendarService } from "@/lib/api/rdvs";

type ServiceLegendProps = {
  services: CalendarService[];
  title: string;
};

export function ServiceLegend({ services, title }: ServiceLegendProps) {
  if (services.length === 0) {
    return null;
  }

  return (
    <div className="rounded-xl border bg-muted/20 p-4">
      <p className="mb-3 text-sm font-medium">{title}</p>
      <div className="flex flex-wrap gap-2">
        {services.map((service) => (
          <span
            key={service.id}
            className="inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs font-medium"
            style={{
              borderColor: `${service.color}66`,
              backgroundColor: `${service.color}18`,
            }}
          >
            <span
              className="size-2.5 rounded-full"
              style={{ backgroundColor: service.color }}
            />
            {service.name}
          </span>
        ))}
      </div>
    </div>
  );
}
