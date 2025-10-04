import React from "react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Link, usePage } from "@inertiajs/react";

export default function Index() {
  const { tickets, view, auth } = usePage().props;

  return (
    <AuthenticatedLayout user={auth.user}>
      <div className="p-6 max-w-5xl mx-auto">
        <div className="flex justify-between items-center mb-6">
          <h1 className="text-2xl font-bold">Ticket Management</h1>
          <Link
            href={route("tickets.create")}
            className="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
          >
            + Create Ticket
          </Link>
        </div>

        {/* --- View Toggle --- */}
        <div className="flex gap-3 mb-6">
          <Link
            href={route("tickets.index", { view: "mine" })}
            className={`px-4 py-2 rounded text-sm font-medium ${
              view === "mine"
                ? "bg-blue-600 text-white"
                : "bg-gray-200 text-gray-700 hover:bg-gray-300"
            }`}
          >
            My Tickets
          </Link>

          {auth.user.is_admin && (
            <Link
              href={route("tickets.index", { view: "all" })}
              className={`px-4 py-2 rounded text-sm font-medium ${
                view === "all"
                  ? "bg-blue-600 text-white"
                  : "bg-gray-200 text-gray-700 hover:bg-gray-300"
              }`}
            >
              All Tickets
            </Link>
          )}
        </div>

        {/* --- Ticket List --- */}
        {tickets.data.length === 0 ? (
          <p className="text-gray-600">No tickets found.</p>
        ) : (
          <div className="bg-white rounded-lg shadow overflow-x-auto">
            <table className="w-full border-collapse">
              <thead className="bg-gray-100">
                <tr>
                  <th className="p-3 border text-left">ID</th>
                  <th className="p-3 border text-left">Name</th>
                  <th className="p-3 border text-left">Status</th>
                  <th className="p-3 border text-left">Created By</th>
                  <th className="p-3 border text-left">Assigned To</th>
                  <th className="p-3 border text-left">Created At</th>
                  <th className="p-3 border text-left">Action</th>
                </tr>
              </thead>
              <tbody>
                {tickets.data.map((ticket) => (
                  <tr key={ticket.id} className="hover:bg-gray-50">
                    <td className="p-3 border">{ticket.id}</td>
                    <td className="p-3 border">{ticket.name}</td>
                    <td className="p-3 border capitalize">{ticket.status}</td>
                    <td className="p-3 border">
                      {ticket.author ? ticket.author.name : "—"}
                    </td>
                    <td className="p-3 border">
                      {ticket.assignee ? ticket.assignee.name : "Not Assigned"}
                    </td>
                    <td className="p-3 border text-sm text-gray-600">
                      {new Date(ticket.created_at).toLocaleString()}
                    </td>
                    <td className="p-3 border text-blue-600">
                      <Link href={route("tickets.show", ticket.id)}>View</Link>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}

        {/* Pagination */}
        {tickets.links && (
          <div className="flex justify-center mt-6 space-x-2">
            {tickets.links.map((link, index) => (
              <Link
                key={index}
                href={link.url || "#"}
                className={`px-3 py-1 rounded text-sm ${
                  link.active
                    ? "bg-blue-600 text-white"
                    : "bg-white text-gray-700 hover:bg-gray-100"
                } ${!link.url && "opacity-50 pointer-events-none"}`}
                dangerouslySetInnerHTML={{ __html: link.label }}
              />
            ))}
          </div>
        )}
      </div>
    </AuthenticatedLayout>
  );
}
