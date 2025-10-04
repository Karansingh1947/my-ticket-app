import React from "react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { Link } from "@inertiajs/react";

export default function Index({ auth, users }) {
  return (
    <AuthenticatedLayout user={auth.user}>
      <div className="p-6 max-w-5xl mx-auto">
        <div className="flex justify-between items-center mb-6">
          <h1 className="text-2xl font-bold">User Management</h1>
          <p className="text-gray-600 text-sm">
            Total Users: {users.total}
          </p>
        </div>

        {users.data.length === 0 ? (
          <div className="bg-white p-6 rounded shadow text-center">
            <p className="text-gray-600">No users found.</p>
          </div>
        ) : (
          <div className="bg-white rounded-lg shadow overflow-x-auto">
            <table className="w-full border-collapse">
              <thead>
                <tr className="bg-gray-100 text-left text-sm font-semibold text-gray-700">
                  <th className="p-3 border">ID</th>
                  <th className="p-3 border">Name</th>
                  <th className="p-3 border">Email</th>
                  <th className="p-3 border">Role</th>
                  <th className="p-3 border">Created At</th>
                </tr>
              </thead>
              <tbody>
                {users.data.map((user) => (
                  <tr
                    key={user.id}
                    className="hover:bg-gray-50 transition-colors"
                  >
                    <td className="p-3 border">{user.id}</td>
                    <td className="p-3 border font-medium text-gray-800">
                      {user.name || "—"}
                    </td>
                    <td className="p-3 border">{user.email || "—"}</td>
                    <td className="p-3 border">
                      <span
                        className={`px-2 py-1 rounded text-xs font-semibold ${
                          user.is_admin
                            ? "bg-green-100 text-green-700"
                            : "bg-gray-200 text-gray-700"
                        }`}
                      >
                        {user.is_admin ? "Admin" : "User"}
                      </span>
                    </td>
                    <td className="p-3 border text-sm text-gray-600">
                      {new Date(user.created_at).toLocaleString()}
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}

        {/* Pagination */}
        {users.links && users.links.length > 3 && (
          <div className="flex justify-center mt-6 space-x-2">
            {users.links.map((link, index) => (
              <Link
                key={index}
                href={link.url || "#"}
                className={`px-3 py-1 rounded border text-sm ${
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
