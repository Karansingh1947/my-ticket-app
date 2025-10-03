import React from "react";
import { Link, usePage } from "@inertiajs/react";

export default function Index() {
  const { tickets } = usePage().props;

  return (
    <div className="p-6">
      <div className="flex justify-between items-center mb-4">
        <h1 className="text-xl font-bold">My Tickets</h1>
        <Link
          href={route("tickets.create")}
          className="px-4 py-2 bg-blue-600 text-white rounded"
        >
          Create Ticket
        </Link>
      </div>

      {tickets.data.length === 0 ? (
        <p>No tickets yet.</p>
      ) : (
        <ul className="space-y-2">
          {tickets.data.map((ticket) => (
            <li
              key={ticket.id}
              className="border rounded p-3 flex justify-between"
            >
              <div>
                <p className="font-semibold">{ticket.name}</p>
                <p className="text-sm text-gray-600">{ticket.status}</p>
              </div>
              <Link
                href={route("tickets.show", ticket.id)}
                className="text-blue-600"
              >
                View
              </Link>
            </li>
          ))}
        </ul>
      )}
    </div>
  );
}
