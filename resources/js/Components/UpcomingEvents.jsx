import React from 'react';
import { Calendar, Cake, User, Flag, ChevronRight } from 'lucide-react';

const events = [
  {
    id: 1,
    type: 'Birthday',
    name: "John Doe's Birthday",
    date: '15 Oct',
    status: 'Today',
    icon: <Cake className="w-5 h-5 text-pink-500" />,
    statusColor: 'bg-green-100 text-green-700',
  },
  {
    id: 2,
    type: 'Leave',
    name: "Sarah Smith - Annual Leave",
    date: '16 Oct',
    status: 'Tomorrow',
    icon: <User className="w-5 h-5 text-blue-500" />,
    statusColor: 'bg-orange-100 text-orange-700',
  },
  {
    id: 3,
    type: 'Holiday',
    name: "Prophet's Birthday",
    date: '20 Oct',
    status: 'Upcoming',
    icon: <Flag className="w-5 h-5 text-red-500" />,
    statusColor: 'bg-gray-100 text-gray-700',
  },
];

const UpcomingEvents = () => {
  return (
    <div className="max-w-md mx-auto bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden font-sans">
      {/* Header */}
      <div className="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
        <div className="flex flex-col items-start gap-4">
          <div className="bg-indigo-50 p-3 rounded-2xl border border-indigo-100/50 flex items-center justify-center w-14 h-14 shadow-sm">
            <Calendar className="w-7 h-7 text-indigo-600" />
          </div>
          <div className="flex flex-col gap-1">
            <h2 className="text-sm font-black text-gray-900 uppercase tracking-[0.15em] ml-0.5">Upcoming Events</h2>
            <ChevronRight className="w-5 h-5 text-gray-400 ml-0.5 cursor-pointer hover:text-indigo-600 transition-colors" />
          </div>
        </div>
      </div>

      {/* List Items */}
      <div className="divide-y divide-gray-50">
        {events.map((event) => (
          <div 
            key={event.id}
            className="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors duration-200 cursor-pointer group"
          >
            <div className="flex items-center gap-4">
              <div className="p-2 bg-gray-50 rounded-full group-hover:bg-white border border-transparent group-hover:border-gray-100 transition-all shadow-sm">
                {event.icon}
              </div>
              <div>
                <h3 className="text-sm font-medium text-gray-900 group-hover:text-indigo-600 transition-colors">
                    {event.name}
                </h3>
                <p className="text-xs text-gray-500 mt-0.5">{event.type} • {event.date}</p>
              </div>
            </div>
            <div className="flex items-center gap-3">
                <span className={`text-[10px] px-2 py-0.5 rounded-full font-bold uppercase tracking-wider ${event.statusColor}`}>
                    {event.status}
                </span>
                <ChevronRight className="w-4 h-4 text-gray-300 group-hover:text-gray-400 transition-colors" />
            </div>
          </div>
        ))}
      </div>

      {/* Footer / Action */}
      <div className="px-6 py-4 bg-gray-50/50 border-t border-gray-50">
        <button className="w-full py-2.5 px-4 bg-white border border-gray-200 rounded-lg text-sm font-semibold text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-100 transition-all duration-200 shadow-sm flex items-center justify-center gap-2">
          View All
        </button>
      </div>
    </div>
  );
};

export default UpcomingEvents;

